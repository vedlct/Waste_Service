"use client";

import { useState } from "react";
import {
  ChevronDown,
  ArrowRight,
  Phone,
} from "lucide-react";

export default function FAQ() {
  const [open, setOpen] = useState(null);

  const faqs = [
    {
      question: "What areas do you cover?",
      answer:
        "We provide our services across London and surrounding areas. You can visit our Areas Covered page to see whether we operate in your location.",
    },
    {
      question: "How much does rubbish removal cost?",
      answer:
        "The price depends on the amount and type of rubbish, access, and location. Contact our team for a quick quotation.",
    },
    {
      question: "Do you offer same-day collection?",
      answer:
        "Yes, depending on availability. Contact us as early as possible and we will do our best to arrange a convenient collection time.",
    },
    {
      question: "Do you provide garden clearance?",
      answer:
        "Yes. We offer garden clearance and green waste removal services for homes, landlords and businesses.",
    },
    {
      question: "Can I book your service online?",
      answer:
        "Yes. You can contact us directly to arrange your service and receive a quotation.",
    },
  ];

  return (
    <main className="min-h-screen bg-[#f7fbff] text-[#11224D]">

      <section className="relative overflow-hidden px-5 pb-24 pt-35">

        <div className="absolute left-0 top-10 h-80 w-80 rounded-full bg-blue-100/40 blur-3xl" />
        <div className="absolute right-0 top-32 h-96 w-96 rounded-full bg-blue-50 blur-3xl" />

        <div className="relative mx-auto max-w-5xl text-center">

          <p className="mb-4 text-sm font-bold uppercase tracking-[0.25em] text-[#087FE8]">
            Frequently Asked Questions
          </p>

          <h1 className="text-3xl font-black leading-tight text-[#11224D] sm:text-4xl">
            Got
            <span className="block text-[#087FE8]">
              Questions?
            </span>
          </h1>

          <p className="mx-auto mt-6 max-w-2xl text-lg leading-8 text-slate-500">
            Find answers to some of the most common questions about our
            rubbish removal and garden services.
          </p>

        </div>


        <div className="relative mx-auto mt-14 max-w-4xl">

          <div className="space-y-4">

            {faqs.map((faq, index) => {
              const isOpen = open === index;

              return (
                <div
                  key={faq.question}
                  className={`group overflow-hidden rounded-2xl border bg-white transition-all duration-500 ${
                    isOpen
                      ? "border-[#087FE8]/30 shadow-xl shadow-blue-100"
                      : "border-slate-100 shadow-sm hover:-translate-y-1 hover:border-blue-100 hover:shadow-xl hover:shadow-blue-100"
                  }`}
                >

                  <button
                    onClick={() => setOpen(isOpen ? null : index)}
                    className="flex w-full items-center justify-between gap-5 px-6 py-6 text-left"
                  >

                    <div className="flex items-center gap-4">

                      <span
                        className={`flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm font-bold transition-all duration-500 ${
                          isOpen
                            ? "bg-[#11224D] text-white"
                            : "bg-[#11224D]/5 text-[#11224D] group-hover:bg-[#11224D] group-hover:text-white"
                        }`}
                      >
                        {String(index + 1).padStart(2, "0")}
                      </span>

                      <span className="font-bold text-[#11224D] transition-colors duration-300 group-hover:text-[#087FE8]">
                        {faq.question}
                      </span>

                    </div>

                    <span
                      className={`flex h-9 w-9 shrink-0 items-center justify-center rounded-full transition-all duration-500 ${
                        isOpen
                          ? "rotate-180 bg-[#087FE8] text-white"
                          : "bg-slate-50 text-[#11224D] group-hover:bg-[#11224D] group-hover:text-white"
                      }`}
                    >
                      <ChevronDown className="h-5 w-5" />
                    </span>

                  </button>


                  <div
                    className={`grid transition-all duration-500 ${
                      isOpen
                        ? "grid-rows-[1fr]"
                        : "grid-rows-[0fr]"
                    }`}
                  >

                    <div className="overflow-hidden">

                      <div className="border-t border-slate-100 px-6 pb-6 pt-5 pl-20 text-sm leading-7 text-slate-500">
                        {faq.answer}
                      </div>

                    </div>

                  </div>

                </div>
              );
            })}

          </div>


          <div className="mt-12 overflow-hidden rounded-3xl border border-sky-200 bg-sky-50 p-8 text-center shadow-2xl shadow-[#11224D]/20 transition-all duration-500 hover:shadow-[#11224D]/30">

            <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white transition-all duration-500 hover:rotate-12 hover:scale-110 hover:bg-sky-100">
              <Phone className="h-6 w-6 text-sky-600" />
            </div>

            <h2 className="mt-5 text-2xl font-black text-[#11224D]">
              Still Have Questions?
            </h2>

            <p className="mx-auto mt-3 max-w-lg text-sm leading-6 text-slate-600">
              Our friendly team is ready to help you with anything you need.
            </p>

            <a
              href="tel:02082266477"
              className="group mt-6 inline-flex items-center gap-3 rounded-full bg-white px-7 py-3 font-bold text-[#11224D] transition-all duration-300 hover:scale-105 hover:bg-[#087FE8] hover:text-white hover:shadow-xl"
            >
              020 8226 6477
              <ArrowRight className="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
            </a>

          </div>

        </div>

      </section>

    </main>
  );
}
