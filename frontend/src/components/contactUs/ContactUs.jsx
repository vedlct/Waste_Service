"use client";

import { useState } from "react";
import { apiPost } from "@/lib/api";
import { FALLBACK_CONTACT, telHref } from "@/lib/site";
import PageBanner from "@/components/ui/PageBanner";
import {
  Phone,
  Mail,
  MapPin,
  Clock,
  ArrowRight,
  CircleCheck,
  CircleAlert,
  LoaderCircle,
} from "lucide-react";

export default function ContactUs({ contact = FALLBACK_CONTACT }) {
  const [submission, setSubmission] = useState({
    state: "idle",
    message: "",
  });

  async function handleSubmit(event) {
    event.preventDefault();

    const form = event.currentTarget;
    const formData = new FormData(form);

    setSubmission({ state: "loading", message: "" });

    // Sent straight to the Laravel API; the old Next.js contact route handler is retired.
    const result = await apiPost("enquiries", Object.fromEntries(formData.entries()));

    if (!result.ok) {
      setSubmission({ state: "error", message: result.message });
      return;
    }

    // A honeypot hit also comes back ok, but without a message; show the normal thanks.
    form.reset();
    setSubmission({
      state: "success",
      message:
        result.message ||
        "Thanks, your enquiry has been sent. We will be in touch shortly.",
    });
  }

  return (
    <main className="min-h-screen bg-mist text-ink">
      <PageBanner
        crumb="Contact"
        eyebrow="Get in touch"
        title="Let's talk"
        accent="about your clearance"
        intro="Have a question or need a free quote? Call the team, send a message, or book online. We're happy to help."
        image="/images/highlight2.jpeg"
      />

      <section className="relative overflow-hidden px-5 pb-24 pt-14 sm:px-8 lg:px-14 xl:px-20">


        <div className="relative mx-auto max-w-7xl">

          <div className="grid gap-10 lg:grid-cols-[0.85fr_1.15fr]">

            <div className="space-y-5">

              <div className="group rounded-3xl border border-slate-100 bg-white p-7 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-blue-100 hover:shadow-2xl hover:shadow-blue-100">

                <div className="flex items-center gap-5">

                  <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-brand-900/5 transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-brand-700">
                    <Phone className="h-6 w-6 text-ink transition-colors duration-300 group-hover:text-white" />
                  </div>

                  <div>
                    <p className="text-sm font-semibold text-slate-400">
                      Call Us
                    </p>

                    <a
                      href={telHref(contact.phone)}
                      className="mt-1 block font-bold text-ink transition-colors duration-300 group-hover:text-brand-600"
                    >
                      {contact.phone}
                    </a>
                  </div>

                </div>

              </div>


              <div className="group rounded-3xl border border-slate-100 bg-white p-7 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-blue-100 hover:shadow-2xl hover:shadow-blue-100">

                <div className="flex items-center gap-5">

                  <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-brand-900/5 transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-brand-700">
                    <Mail className="h-6 w-6 text-ink transition-colors duration-300 group-hover:text-white" />
                  </div>

                  <div>
                    <p className="text-sm font-semibold text-slate-400">
                      Email Us
                    </p>

                    <a
                      href={`mailto:${contact.email}`}
                      className="mt-1 block font-bold text-ink transition-colors duration-300 group-hover:text-brand-600"
                    >
                      {contact.email}
                    </a>
                  </div>

                </div>

              </div>


              <div className="group rounded-3xl border border-slate-100 bg-white p-7 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-blue-100 hover:shadow-2xl hover:shadow-blue-100">

                <div className="flex items-center gap-5">

                  <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-brand-900/5 transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-brand-700">
                    <MapPin className="h-6 w-6 text-ink transition-colors duration-300 group-hover:text-white" />
                  </div>

                  <div>
                    <p className="text-sm font-semibold text-slate-400">
                      Location
                    </p>

                    <p className="mt-1 font-bold text-ink">
                      {contact.location}
                    </p>
                  </div>

                </div>

              </div>


              <div className="group rounded-3xl border border-slate-100 bg-white p-7 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-blue-100 hover:shadow-2xl hover:shadow-blue-100">

                <div className="flex items-center gap-5">

                  <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-brand-900/5 transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-brand-700">
                    <Clock className="h-6 w-6 text-ink transition-colors duration-300 group-hover:text-white" />
                  </div>

                  <div>
                    <p className="text-sm font-semibold text-slate-400">
                      Opening Hours
                    </p>

                    <p className="mt-1 font-bold text-ink">
                      {contact.openingHours}
                    </p>
                  </div>

                </div>

              </div>

            </div>


            <div className="group rounded-[2rem] border border-slate-100 bg-white p-6 shadow-xl shadow-ink/5 transition-all duration-500 hover:shadow-2xl hover:shadow-blue-100 sm:p-8 lg:p-10">

              <div className="mb-8">

                <p className="text-sm font-bold uppercase tracking-[0.2em] text-brand-600">
                  Send Us A Message
                </p>

                <h2 className="mt-3 text-4xl font-bold text-ink sm:text-5xl uppercase leading-[0.95]">
                  How Can We Help?
                </h2>

                <p className="mt-3 text-sm leading-6 text-slate-500">
                  Fill out the form below and our team will get back to you
                  as soon as possible.
                </p>

              </div>


              <form
                className="space-y-6"
                onSubmit={handleSubmit}
                aria-busy={submission.state === "loading"}
              >

                <div className="sr-only" aria-hidden="true">
                  <label htmlFor="company">Company website</label>
                  <input
                    id="company"
                    name="company"
                    type="text"
                    tabIndex={-1}
                    autoComplete="off"
                  />
                </div>

                <div className="grid gap-6 sm:grid-cols-2">

                  <div className="group/input">

                    <label htmlFor="contact-name" className="mb-2 block text-sm font-bold text-ink">
                      Your Name
                    </label>

                    <input
                      id="contact-name"
                      name="name"
                      type="text"
                      placeholder="Enter your name"
                      autoComplete="name"
                      minLength={2}
                      maxLength={100}
                      required
                      className="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm outline-none transition-all duration-300 placeholder:text-slate-400 focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-blue-100 hover:border-ink/30"
                    />

                  </div>


                  <div className="group/input">

                    <label htmlFor="contact-phone" className="mb-2 block text-sm font-bold text-ink">
                      Phone Number
                    </label>

                    <input
                      id="contact-phone"
                      name="phone"
                      type="tel"
                      placeholder="Enter your phone number"
                      autoComplete="tel"
                      minLength={7}
                      maxLength={30}
                      required
                      className="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm outline-none transition-all duration-300 placeholder:text-slate-400 focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-blue-100 hover:border-ink/30"
                    />

                  </div>

                </div>


                <div>

                  <label htmlFor="contact-email" className="mb-2 block text-sm font-bold text-ink">
                    Email Address
                  </label>

                  <input
                    id="contact-email"
                    name="email"
                    type="email"
                    placeholder="Enter your email"
                    autoComplete="email"
                    maxLength={150}
                    required
                    className="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm outline-none transition-all duration-300 placeholder:text-slate-400 focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-blue-100 hover:border-ink/30"
                  />

                </div>


                <div>

                  <label htmlFor="contact-service" className="mb-2 block text-sm font-bold text-ink">
                    Service Required
                  </label>

                  <select
                    id="contact-service"
                    name="service"
                    required
                    className="w-full cursor-pointer rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-500 outline-none transition-all duration-300 focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-blue-100 hover:border-ink/30"
                  >
                    <option value="">Select a service</option>
                    <option value="house">House clearance</option>
                    <option value="garden">Garden clearance</option>
                    <option value="office">Office clearance</option>
                    <option value="builders">Builders waste removal</option>
                    <option value="other">Other service</option>
                  </select>

                </div>


                <div>

                  <label htmlFor="contact-message" className="mb-2 block text-sm font-bold text-ink">
                    Your Message
                  </label>

                  <textarea
                    id="contact-message"
                    name="message"
                    rows="5"
                    placeholder="Tell us how we can help..."
                    minLength={10}
                    maxLength={2000}
                    required
                    className="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm outline-none transition-all duration-300 placeholder:text-slate-400 focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-blue-100 hover:border-ink/30"
                  />

                </div>


                {submission.state !== "idle" && submission.state !== "loading" && (
                  <div
                    className={`flex items-start gap-3 rounded-xl border px-4 py-3 text-sm font-medium ${
                      submission.state === "success"
                        ? "border-emerald-200 bg-emerald-50 text-emerald-800"
                        : "border-red-200 bg-red-50 text-red-700"
                    }`}
                    role={submission.state === "error" ? "alert" : "status"}
                    aria-live="polite"
                  >
                    {submission.state === "success" ? (
                      <CircleCheck className="mt-0.5 h-5 w-5 shrink-0" />
                    ) : (
                      <CircleAlert className="mt-0.5 h-5 w-5 shrink-0" />
                    )}
                    <span>{submission.message}</span>
                  </div>
                )}

                <button
                  type="submit"
                  disabled={submission.state === "loading"}
                  className="group flex w-full items-center justify-center gap-3 rounded-xl bg-brand-900 px-6 py-4 font-bold text-white transition-all duration-300 hover:-translate-y-1 hover:bg-brand-600 hover:shadow-xl hover:shadow-blue-200 disabled:cursor-wait disabled:opacity-70 disabled:hover:translate-y-0"
                >
                  {submission.state === "loading" ? "Sending..." : "Send Message"}

                  <span className="flex h-7 w-7 items-center justify-center rounded-full bg-white text-ink transition-all duration-300 group-hover:translate-x-1">
                    {submission.state === "loading" ? (
                      <LoaderCircle className="h-4 w-4 animate-spin" />
                    ) : (
                      <ArrowRight className="h-4 w-4" />
                    )}
                  </span>

                </button>

              </form>

            </div>

          </div>

        </div>

      </section>

    </main>
  );
}
