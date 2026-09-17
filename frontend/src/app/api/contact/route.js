const SERVICES = {
  house: "House clearance",
  garden: "Garden clearance",
  office: "Office clearance",
  builders: "Builders waste removal",
  other: "Other service",
};

const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function clean(value) {
  return typeof value === "string" ? value.trim() : "";
}

function escapeHtml(value) {
  return value
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function validationMessage({ name, phone, email, service, message }) {
  if (name.length < 2 || name.length > 100) {
    return "Please enter a valid name.";
  }

  if (phone.length < 7 || phone.length > 30) {
    return "Please enter a valid phone number.";
  }

  if (email.length > 150 || !EMAIL_PATTERN.test(email)) {
    return "Please enter a valid email address.";
  }

  if (!Object.hasOwn(SERVICES, service)) {
    return "Please choose a service.";
  }

  if (message.length < 10 || message.length > 2000) {
    return "Please enter a message between 10 and 2,000 characters.";
  }

  return null;
}

export async function POST(request) {
  const contentLength = Number(request.headers.get("content-length") || 0);

  if (contentLength > 20_000) {
    return Response.json(
      { message: "Your enquiry is too large to submit." },
      { status: 413 },
    );
  }

  let body;

  try {
    body = await request.json();
  } catch {
    return Response.json(
      { message: "We could not read your enquiry. Please try again." },
      { status: 400 },
    );
  }

  const enquiry = {
    name: clean(body.name),
    phone: clean(body.phone),
    email: clean(body.email).toLowerCase(),
    service: clean(body.service),
    message: clean(body.message),
  };

  // Silently accept honeypot submissions so automated spam does not retry.
  if (clean(body.company)) {
    return Response.json({ ok: true });
  }

  const error = validationMessage(enquiry);

  if (error) {
    return Response.json({ message: error }, { status: 400 });
  }

  const apiKey = process.env.RESEND_API_KEY;
  const recipient = process.env.CONTACT_TO_EMAIL;
  const sender =
    process.env.CONTACT_FROM_EMAIL ||
    "Waste Services Website <onboarding@resend.dev>";

  if (!apiKey || !recipient) {
    return Response.json(
      {
        message:
          "Online enquiries are temporarily unavailable. Please call us instead.",
      },
      { status: 503 },
    );
  }

  const serviceName = SERVICES[enquiry.service];
  const safe = Object.fromEntries(
    Object.entries(enquiry).map(([key, value]) => [key, escapeHtml(value)]),
  );

  let delivery;

  try {
    delivery = await fetch("https://api.resend.com/emails", {
      method: "POST",
      headers: {
        Authorization: `Bearer ${apiKey}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        from: sender,
        to: [recipient],
        reply_to: enquiry.email,
        subject: `New ${serviceName} enquiry from ${enquiry.name}`,
        text: [
          `Name: ${enquiry.name}`,
          `Phone: ${enquiry.phone}`,
          `Email: ${enquiry.email}`,
          `Service: ${serviceName}`,
          "",
          enquiry.message,
        ].join("\n"),
        html: `
          <h2>New website enquiry</h2>
          <p><strong>Name:</strong> ${safe.name}</p>
          <p><strong>Phone:</strong> ${safe.phone}</p>
          <p><strong>Email:</strong> ${safe.email}</p>
          <p><strong>Service:</strong> ${escapeHtml(serviceName)}</p>
          <p><strong>Message:</strong></p>
          <p>${safe.message.replaceAll("\n", "<br>")}</p>
        `,
      }),
    });
  } catch {
    return Response.json(
      {
        message:
          "We could not send your enquiry right now. Please try again or call us.",
      },
      { status: 502 },
    );
  }

  if (!delivery.ok) {
    return Response.json(
      {
        message:
          "We could not send your enquiry right now. Please try again or call us.",
      },
      { status: 502 },
    );
  }

  return Response.json({
    ok: true,
    message: "Thanks — your enquiry has been sent. We’ll be in touch shortly.",
  });
}
