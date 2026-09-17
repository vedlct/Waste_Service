"use client";
 
import { useState } from "react";
import { Star, Plus, X } from "lucide-react";
import { Card, CardContent } from "@/components/ui/card";
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel";
 
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
 
function Stars({ count }) {
  return (
    <div className="flex gap-0.5" aria-label={`${count} out of 5 stars`}>
      {Array.from({ length: 5 }).map((_, i) => (
        <Star key={i} size={14} className={i < count ? "fill-[#2563eb] text-[#2563eb]" : "fill-none text-[#d1d5db]"} />
      ))}
    </div>
  );
}

function AddReviewForm({ onClose, onSubmit }) {
  const [name, setName] = useState("");
  const [text, setText] = useState("");
  const [rating, setRating] = useState(5);

  const handleSubmit = () => {
    if (!name.trim() || !text.trim()) return;
    onSubmit({ name, text, rating, time: "Just now" });
    onClose();
  };

  return (
    <div className="fixed inset-0 flex items-center justify-center z-50 p-4 overflow-y-auto">
      <Card className="w-full max-w-sm relative bg-[#ffffff] text-[#1f2937] max-h-[calc(100dvh-2rem)] overflow-y-auto">
        <button onClick={onClose} className="absolute top-3 right-3 text-[#9ca3af]" aria-label="Close review form"><X size={18} /></button>
        <CardContent className="pt-6 flex flex-col gap-3">
          <h3 className="font-semibold text-[#1f2937]">Write a review</h3>
          <input value={name} onChange={(e) => setName(e.target.value)} placeholder="Your name" aria-label="Your name" className="border rounded-md px-3 py-2 text-sm bg-[#ffffff] text-[#1f2937] border-[#d1d5db] min-w-0" />
          <div className="flex gap-1">
            {Array.from({ length: 5 }).map((_, i) => (
              <button key={i} onClick={() => setRating(i + 1)} aria-label={`${i + 1} stars`}>
                <Star size={20} className={i < rating ? "fill-[#2563eb] text-[#2563eb]" : "fill-none text-[#d1d5db]"} />
              </button>
            ))}
          </div>
          <textarea value={text} onChange={(e) => setText(e.target.value)} placeholder="Your review" aria-label="Your review" rows={3} className="border rounded-md px-3 py-2 text-sm resize-none bg-[#ffffff] text-[#1f2937] border-[#d1d5db] min-w-0" />
          <button onClick={handleSubmit} className="rounded-md py-2 text-sm font-medium bg-[#2563eb] text-[#ffffff]">Submit</button>
        </CardContent>
      </Card>
    </div>
  );
}

export default function Review() {
  const [reviews, setReviews] = useState(initialReviews);
  const [showForm, setShowForm] = useState(false);

  return (
    <section className="w-full bg-linear-to-r from-white via-[#B3E4F8]/40 to-white py-8 text-[#171717]">
      <div className="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div className="max-[400px]:flex-wrap flex items-center justify-between mb-4 gap-3">
        <h2 className="text-lg text-[#11224D] font-semibold bg-white px-3 py-1 rounded-full border border-[#2563EB]/40 shadow">Customer reviews</h2>
        <button onClick={() => setShowForm(true)} className="flex items-center gap-1 text-sm px-4 py-2 rounded-full bg-[#2563eb] hover:bg-white text-[#ffffff] font-semibold hover:text-[#2563eb] hover:border border-[#2563eb] shadow-md shrink-0">
          <Plus size={14} />Write a review
        </button>
      </div>
      <Carousel opts={{ align: "start" }} className="w-full px-8">
        <CarouselContent className="-ml-1">
          {reviews.map((review, index) => (
            <CarouselItem key={index} className="pl-1 basis-full sm:basis-1/2 lg:basis-1/3">
              <div className="p-1 h-full">
                <Card className="w-full h-60 bg-[#ffffff] text-[#4b5563]">
                  <CardContent className="p-4 flex flex-col gap-2 flex-1 min-h-0">
                    <p className="font-bold text-base text-[#1d4ed8] overflow-hidden text-ellipsis whitespace-nowrap shrink-0">{review.name}</p>
                    <p className="text-xs -mt-1 text-[#9ca3af] shrink-0">{review.time}</p>
                    <Stars count={review.rating} />
                    <p className="text-sm line-clamp-4 text-[#4b5563] wrap-anywhere">{review.text}</p>
                  </CardContent>
                </Card>
              </div>
            </CarouselItem>
          ))}
        </CarouselContent>
        <CarouselPrevious className="left-0 bg-[#ffffff] text-[#171717] border-[#d1d5db]" />
        <CarouselNext className="right-0 bg-[#ffffff] text-[#171717] border-[#d1d5db]" />
      </Carousel>
      {showForm && <AddReviewForm onClose={() => setShowForm(false)} onSubmit={(review) => setReviews((prev) => [...prev, review])} />}
      </div>
    </section>
  );
}