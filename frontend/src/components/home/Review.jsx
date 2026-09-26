"use client";
 
import { useState } from "react";
import { Plus, Quote, Star, X } from "lucide-react";
import { apiPost } from "@/lib/api";
import { Card, CardContent } from "@/components/ui/card";
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel";
 
// Shown when the backend is unreachable, so the section never renders empty.
const initialReviews = [
  {
    name: "Sue Lewis",
    time: "8 months ago",
    rating: 5,
    text: "Excellent Removal Service. I needed to move some stuff in a Storage facility",
  },
  {
    name: "Mr Blue sky",
    time: "5 months ago",
    rating: 5,
    text: "Excellent professional reliable friendly team Mr Tee and his team are great",
  },
  {
    name: "Julie Smith",
    time: "8 months ago",
    rating: 4,
    text: "I'm glad I decided to go with Mr Tee removals. After long hours of drive to my new house.",
  },
  {
    name: "Julie Smith",
    time: "11 months ago",
    rating: 4,
    text: "I'm glad I decided to go with Mr Tee removals. After long hours of drive to my new house.",
  },
  {
    name: "Julie Smith",
    time: "2 months ago",
    rating: 3,
    text: "I'm glad I decided to go with Mr Tee removals. After long hours of drive to my new house.",
  },
  {
    name: "Julie Smith",
    time: "1 months ago",
    rating: 4,
    text: "I'm glad I decided to go with Mr Tee removals. After long hours of drive to my new house.",
  },
];
 
function Stars({ count, size = 16 }) {
  return (
    <div className="flex gap-0.5" role="img" aria-label={`${count} out of 5 stars`}>
      {Array.from({ length: 5 }).map((_, i) => (
        <Star key={i} size={size} aria-hidden="true" className={i < count ? "fill-amber-400 text-amber-400" : "fill-none text-[#d1d5db]"} />
      ))}
    </div>
  );
}

function AddReviewForm({ onClose }) {
  const [name, setName] = useState("");
  const [text, setText] = useState("");
  const [rating, setRating] = useState(5);
  const [honeypot, setHoneypot] = useState("");
  const [status, setStatus] = useState({ state: "idle", message: "" });

  const handleSubmit = async () => {
    if (!name.trim() || !text.trim() || status.state === "sending") return;

    setStatus({ state: "sending", message: "" });

    // Reviews are stored as pending and only appear once the office has approved them,
    // so nothing is added to the carousel here.
    const result = await apiPost("reviews", {
      reviewer_name: name,
      rating,
      body: text,
      website: honeypot,
    });

    setStatus(result.ok
      ? { state: "sent", message: result.message || "Thanks for your review. It will appear once our team has checked it." }
      : { state: "error", message: result.message });
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-brand-900/60 p-4 backdrop-blur-sm">
      <Card className="w-full max-w-sm relative bg-[#ffffff] text-ink max-h-[calc(100dvh-2rem)] overflow-y-auto">
        <button onClick={onClose} className="absolute top-3 right-3 text-[#9ca3af]" aria-label="Close review form"><X size={18} /></button>
        <CardContent className="pt-6 flex flex-col gap-3">
          <h3 className="font-semibold text-ink">Write a review</h3>
          {status.state === "sent" ? (
            <>
              <p className="text-sm text-[#4b5563]" role="status">{status.message}</p>
              <button onClick={onClose} className="rounded-md py-2 text-sm font-medium bg-brand-600 text-[#ffffff]">Close</button>
            </>
          ) : (
            <>
              <input value={name} onChange={(e) => setName(e.target.value)} placeholder="Your name" aria-label="Your name" className="border rounded-md px-3 py-2 text-sm bg-[#ffffff] text-ink border-[#d1d5db] min-w-0" />
              {/* Honeypot: hidden from people, filled by bots. */}
              <input value={honeypot} onChange={(e) => setHoneypot(e.target.value)} name="website" tabIndex={-1} autoComplete="off" aria-hidden="true" className="hidden" />
              <div className="flex gap-1">
                {Array.from({ length: 5 }).map((_, i) => (
                  <button key={i} onClick={() => setRating(i + 1)} aria-label={`${i + 1} stars`}>
                    <Star size={20} className={i < rating ? "fill-amber-400 text-amber-400" : "fill-none text-[#d1d5db]"} />
                  </button>
                ))}
              </div>
              <textarea value={text} onChange={(e) => setText(e.target.value)} placeholder="Your review" aria-label="Your review" rows={3} className="border rounded-md px-3 py-2 text-sm resize-none bg-[#ffffff] text-ink border-[#d1d5db] min-w-0" />
              {status.state === "error" && <p className="text-sm text-red-600" role="alert">{status.message}</p>}
              <button onClick={handleSubmit} disabled={status.state === "sending"} className="rounded-md py-2 text-sm font-medium bg-brand-600 text-[#ffffff] disabled:opacity-60">
                {status.state === "sending" ? "Sending..." : "Submit"}
              </button>
            </>
          )}
        </CardContent>
      </Card>
    </div>
  );
}

export default function Review({ reviews: remoteReviews }) {
  const reviews = remoteReviews?.length ? remoteReviews : initialReviews;
  const [showForm, setShowForm] = useState(false);
  const average = reviews.reduce((sum, review) => sum + (review.rating || 0), 0) / reviews.length;

  return (
    <section className="bg-mist py-20 text-ink sm:py-24">
      <div className="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
          <div>
            <p className="eyebrow">Reviews</p>
            <h2 className="mt-3 text-4xl font-bold uppercase leading-[0.95] sm:text-5xl">
              What our customers
              <span className="block text-brand-600">say about us</span>
            </h2>
            <div className="mt-5 flex items-center gap-3">
              <Stars count={Math.round(average)} size={20} />
              <p className="text-sm font-semibold text-slate-600">
                {average.toFixed(1)} out of 5 from {reviews.length} {reviews.length === 1 ? "review" : "reviews"}
              </p>
            </div>
          </div>
          <button
            onClick={() => setShowForm(true)}
            className="inline-flex shrink-0 items-center gap-2 self-start rounded-lg border border-line bg-white px-5 py-3 text-sm font-bold text-ink transition-colors hover:border-brand-300 hover:bg-brand-50 md:self-auto"
          >
            <Plus size={16} aria-hidden="true" />
            Write a review
          </button>
        </div>

        <Carousel opts={{ align: "start" }} className="mt-10 w-full">
          <CarouselContent className="-ml-4">
            {reviews.map((review, index) => (
              <CarouselItem key={index} className="basis-full pl-4 sm:basis-1/2 lg:basis-1/3">
                <figure className="flex h-full flex-col rounded-2xl border border-line bg-white p-6 shadow-sm">
                  <div className="flex items-center justify-between">
                    <Stars count={review.rating} />
                    <Quote className="size-8 fill-brand-100 text-brand-100" aria-hidden="true" />
                  </div>
                  <blockquote className="mt-4 flex-1 text-[15px] leading-7 text-slate-700 wrap-anywhere line-clamp-6">
                    {review.text}
                  </blockquote>
                  <figcaption className="mt-6 flex items-center gap-3 border-t border-line pt-4">
                    <span className="grid size-10 shrink-0 place-items-center rounded-full bg-brand-600 font-display text-lg font-bold text-white" aria-hidden="true">
                      {review.name?.trim()?.[0]?.toUpperCase() ?? "?"}
                    </span>
                    <span className="min-w-0">
                      <span className="block truncate font-bold text-ink">{review.name}</span>
                      <span className="block text-xs text-slate-500">{review.time}</span>
                    </span>
                  </figcaption>
                </figure>
              </CarouselItem>
            ))}
          </CarouselContent>
          <div className="mt-6 flex justify-end gap-2">
            <CarouselPrevious className="static translate-y-0 size-11 border-line bg-white text-ink hover:bg-brand-50" />
            <CarouselNext className="static translate-y-0 size-11 border-line bg-white text-ink hover:bg-brand-50" />
          </div>
        </Carousel>
        {showForm && <AddReviewForm onClose={() => setShowForm(false)} />}
      </div>
    </section>
  );
}
