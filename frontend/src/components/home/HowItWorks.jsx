import Image from "next/image";
import {
  ClipboardList,
  FileText,
  Package,
  Truck,
  ShieldCheck,
  Clock3,
  Users,
  Headphones,
  ArrowRight,
} from "lucide-react";

export default function HowItWorks() {
  const steps = [
    {
      number: "01",
      title: "Survey",
      description:
        "Our moving consultant will come visit you and survey your property.",
      icon: ClipboardList,
    },
    {
      number: "02",
      title: "Quotation & Acceptance",
      description:
        "You will usually receive our quotation within 24 hours, which can be accepted online or by replying to our email.",
      icon: FileText,
    },
    {
      number: "03",
      title: "Boxes & Packing Kit",
      description:
        "Once you accept the quotation, we'll deliver free boxes and packing materials to help you pack with ease.",
      icon: Package,
    },
    {
      number: "04",
      title: "Moving Day",
      description:
        "Our professional team arrives on time, protects your items, and loads everything safely for a smooth move.",
      icon: Truck,
    },
  ];

  const benefits = [
    {
      title: "Fully Insured",
      text: "Safe hands",
      icon: ShieldCheck,
    },
    {
      title: "On-Time",
      text: "We value time",
      icon: Clock3,
    },
    {
      title: "Expert Team",
      text: "Trained staff",
      icon: Users,
    },
    {
      title: "24/7 Support",
      text: "Always here",
      icon: Headphones,
    },
  ];

  return (
    <section className="relative overflow-hidden bg-slate-50 px-4 py-20 sm:px-6 lg:px-10 xl:px-16 bg-linear-to-b from-[#E9F4FC] via-white to-[#E9F4FC]">

      <div className="absolute -left-32 top-20 h-72 w-72 rounded-full bg-blue-100/50 blur-3xl" />
      <div className="absolute -right-32 bottom-10 h-96 w-96 rounded-full bg-blue-100/50 blur-3xl" />

      <div className="relative mx-auto max-w-7xl">

        <div className="mb-14 text-center">
          <span className="inline-block rounded-full border border-blue-100 bg-blue-50 px-5 py-2 text-xs font-bold uppercase tracking-[0.2em] text-blue-600 transition-all duration-300 hover:border-blue-600 hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-200">
            Our Simple Process
          </span>

          <h2 className="mt-5 text-3xl font-black tracking-tight text-slate-900 transition-all duration-500 hover:text-blue-600 sm:text-4xl lg:text-4xl">
            How It Works
          </h2>

          <p className="mx-auto mt-5 max-w-2xl text-base leading-7 text-slate-500 sm:text-lg">
            We make your move simple, safe and stress-free with our
            straightforward 4-step process.
          </p>
        </div>

        <div className="grid items-center gap-14 lg:grid-cols-2 lg:gap-20">

          <div className="group relative">

            <div className="absolute -left-8 -top-8 hidden h-24 w-24 rounded-full border-2 border-blue-200 transition-all duration-700 group-hover:scale-125 group-hover:border-blue-500 lg:block" />

            <div className="absolute -bottom-5 -right-5 hidden h-28 w-28 rounded-full bg-blue-100 transition-all duration-700 group-hover:scale-125 group-hover:bg-blue-200 lg:block" />

            <div className="relative mx-auto aspect-square w-[280px] overflow-hidden rounded-full border-[10px] border-white shadow-2xl shadow-blue-100 transition-all duration-700 group-hover:scale-[1.03] group-hover:border-blue-50 group-hover:shadow-blue-300 sm:w-[400px] lg:w-[470px]">

              <Image
                src="/images/rubbishVan.jpg"
                alt="Moving team carrying boxes"
                fill
                priority
                className="object-cover transition-transform duration-1000 group-hover:scale-110"
              />

              <div className="absolute inset-0 bg-blue-600/0 transition-all duration-700 group-hover:bg-blue-600/10" />

              <div className="absolute bottom-8 left-8 rounded-2xl bg-white/90 px-5 py-3 opacity-0 shadow-xl backdrop-blur-md transition-all duration-500 group-hover:bottom-12 group-hover:opacity-100">
                <p className="text-xs font-semibold uppercase tracking-wider text-blue-600">
                  Moving Made Easy
                </p>
                <p className="mt-1 font-bold text-slate-900">
                  Professional Service
                </p>
              </div>
            </div>

            <div className="absolute bottom-7 right-[calc(50%-145px)] h-12 w-12 rounded-full border-8 border-white bg-blue-600 shadow-lg transition-all duration-500 hover:scale-125 hover:rotate-180 sm:right-[calc(50%-205px)] lg:right-[calc(50%-240px)]" />

            <div className="mt-10 rounded-3xl border border-slate-100 bg-white p-5 shadow-xl shadow-slate-200/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-100">

              <div className="grid grid-cols-2 gap-5 md:grid-cols-4">

                {benefits.map((item) => {
                  const Icon = item.icon;

                  return (
                    <div
                      key={item.title}
                      className="group/item flex cursor-pointer items-center gap-3 rounded-2xl p-2 transition-all duration-300 hover:-translate-y-1 hover:bg-blue-50"
                    >
                      <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 transition-all duration-500 group-hover/item:rotate-12 group-hover/item:scale-110 group-hover/item:bg-blue-600">
                        <Icon className="h-5 w-5 text-blue-600 transition-colors duration-300 group-hover/item:text-white" />
                      </div>

                      <div>
                        <h4 className="text-xs font-bold text-slate-900 transition-colors duration-300 group-hover/item:text-blue-600 sm:text-sm">
                          {item.title}
                        </h4>

                        <p className="mt-1 text-[11px] text-slate-400">
                          {item.text}
                        </p>
                      </div>
                    </div>
                  );
                })}

              </div>
            </div>
          </div>

          <div className="relative">

            <div className="absolute bottom-8 left-6 top-8 hidden w-[2px] bg-gradient-to-b from-blue-600 via-blue-300 to-transparent sm:block" />

            <div className="space-y-5">

              {steps.map((step) => {
                const Icon = step.icon;

                return (
                  <div
                    key={step.number}
                    className="group relative flex cursor-pointer gap-4 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100 sm:gap-5 sm:p-6"
                  >

                    <div className="relative z-10 shrink-0">
                      <div className="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-sm font-black text-white shadow-lg shadow-blue-200 transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 group-hover:bg-blue-700 group-hover:shadow-blue-300">
                        {step.number}
                      </div>
                    </div>

                    <div className="shrink-0">
                      <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 shadow-lg transition-all duration-500 group-hover:scale-110 group-hover:rotate-3 group-hover:from-blue-600 group-hover:to-indigo-700 group-hover:shadow-blue-300 sm:h-16 sm:w-16">
                        <Icon className="h-7 w-7 text-white transition-transform duration-500 group-hover:scale-110" />
                      </div>
                    </div>

                    <div className="min-w-0 flex-1">

                      <div className="flex items-start justify-between gap-3">

                        <h3 className="text-lg font-bold text-slate-900 transition-colors duration-300 group-hover:text-blue-600 sm:text-xl">
                          {step.title}
                        </h3>

                        <span className="hidden text-3xl font-black text-slate-100 transition-all duration-500 group-hover:text-blue-100 sm:block">
                          {step.number}
                        </span>

                      </div>

                      <p className="mt-2 text-sm leading-6 text-slate-500 transition-colors duration-300 group-hover:text-slate-600 sm:text-base">
                        {step.description}
                      </p>

                      <div className="mt-3 flex items-center gap-2 text-sm font-semibold text-blue-500 opacity-0 transition-all duration-500 group-hover:translate-x-1 group-hover:opacity-100">
                        Learn more
                        <ArrowRight className="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
                      </div>

                    </div>

                    <div className="absolute inset-x-0 bottom-0 h-1 origin-left scale-x-0 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 transition-transform duration-500 group-hover:scale-x-100" />

                  </div>
                );
              })}

            </div>
          </div>

        </div>
      </div>
    </section>
  );
}