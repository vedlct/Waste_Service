import Image from "next/image";
import {
  ArrowUpRight,
  CheckCircle2,
  Clock3,
  ShieldCheck,
  Users,
} from "lucide-react";

export default function AboutUs() {
  const features = [
    {
      icon: ShieldCheck,
      title: "Fully Insured",
      text: "Your belongings are handled with care.",
    },
    {
      icon: Users,
      title: "Expert Team",
      text: "Experienced professionals you can trust.",
    },
    {
      icon: Clock3,
      title: "24/7 Service",
      text: "Flexible support whenever you need us.",
    },
  ];

  return (
    <section className="relative overflow-hidden bg-white px-5 py-20 sm:px-8 lg:px-14 xl:px-20 bg-linear-to-b from-[#FFFFFF] to-[#E9F4FC]">

      <div className="absolute -left-32 top-20 h-80 w-80 rounded-full bg-[#0492E8]/5 blur-3xl" />
      <div className="absolute -right-32 bottom-10 h-96 w-96 rounded-full bg-blue-100/40 blur-3xl" />

      <div className="relative mx-auto max-w-7xl">

        <div className="grid items-center gap-14 lg:grid-cols-2 lg:gap-20">

          <div className="group relative">

            <div className="absolute -left-5 -top-5 hidden h-24 w-24 rounded-full border-2 border-[#0492E8]/10 transition-all duration-700 group-hover:scale-125 group-hover:border-[#0492E8]/30 lg:block" />

            <div className="absolute -bottom-8 -right-8 hidden h-28 w-28 rounded-full bg-[#0492E8]/5 transition-all duration-700 group-hover:scale-125 group-hover:bg-[#0492E8]/10 lg:block" />

            <div className="relative overflow-hidden rounded-[2rem] border-[10px] border-white bg-white shadow-2xl shadow-[#0492E8]/10 transition-all duration-700 group-hover:-translate-y-2 group-hover:shadow-2xl group-hover:shadow-[#0492E8]/20">

              <div className="relative aspect-[4/3] overflow-hidden rounded-[1.4rem]">

                <Image
                  src="/images/rubbishVan.jpg"
                  alt="Professional removal company"
                  fill
                  priority
                  className="object-cover transition-transform duration-1000 group-hover:scale-110"
                />

                <div className="absolute inset-0 bg-gradient-to-tr from-[#0492E8]/50 via-transparent to-transparent opacity-60 transition-all duration-700 group-hover:opacity-30" />

                <div className="absolute inset-0 bg-[#0492E8]/0 transition-all duration-700 group-hover:bg-[#0492E8]/10" />

                <div className="absolute bottom-5 left-5 translate-y-4 opacity-0 transition-all duration-500 group-hover:translate-y-0 group-hover:opacity-100">

                  <div className="rounded-2xl border border-sky-200 bg-white/90 px-5 py-4 shadow-xl backdrop-blur-md">

                    <p className="text-xs font-bold uppercase tracking-widest text-[#11224D]">
                      Professional Moving
                    </p>

                    <p className="mt-1 text-sm font-semibold text-slate-700">
                      Moving made simple.
                    </p>

                  </div>

                </div>

              </div>
            </div>

            <div className="absolute -bottom-6 left-8 rounded-2xl bg-[#0492E8] px-6 py-4 text-[#102a4c] shadow-xl shadow-[#0492E8]/25 transition-all duration-500 hover:-translate-y-2 hover:scale-105 hover:shadow-2xl hover:shadow-[#0492E8]/30">

              <p className="text-2xl font-black">
                10+
              </p>

              <p className="text-xs font-medium text-[#102a4c]/70">
                Years Experience
              </p>

            </div>

          </div>


          <div>

            <div className="mb-5 inline-flex items-center gap-3">

              <span className="h-[2px] w-10 bg-[#0492E8] transition-all duration-500 hover:w-16" />

              <span className="text-sm font-bold uppercase tracking-[0.2em] text-[#11224D]">
                About Us
              </span>

            </div>


            <h2 className="max-w-2xl text-3xl font-black leading-tight tracking-tight text-slate-900 sm:text-4xl lg:text-4xl">

              We are a

              <span className="relative mx-2 inline-block text-[#11224D] transition-all duration-500 hover:tracking-wide">
                Professional
              </span>

              <br />

              <span className="text-[#11224D] transition-all duration-500 hover:text-blue-600">
                Removal Company
              </span>

            </h2>


            <div className="mt-6 h-1 w-16 rounded-full bg-[#0492E8] transition-all duration-500 hover:w-32" />


            <p className="mt-7 text-base leading-8 text-slate-600 transition-colors duration-300 hover:text-slate-800 sm:text-lg">
              With a friendly and experienced team operating twenty-four hours
              a day, we ensure that your home or office move goes smoothly
              across the UK.
            </p>


            <p className="mt-5 text-base leading-8 text-slate-600 transition-colors duration-300 hover:text-slate-800 sm:text-lg">
              Moving home or office can be a stressful experience. Our
              professional removal team takes care of every detail, providing
              flexible solutions designed around your individual needs.
            </p>


            <div className="mt-7 space-y-3">

              {[
                "Professional and experienced moving team",
                "Flexible removal services across the UK",
                "Careful handling of your valuable belongings",
              ].map((item) => (

                <div
                  key={item}
                  className="group flex cursor-pointer items-center gap-3 rounded-xl p-2 transition-all duration-300 hover:translate-x-2 hover:bg-[#0492E8]/5"
                >

                  <CheckCircle2
                    className="h-5 w-5 shrink-0 text-[#11224D] transition-all duration-300 group-hover:scale-125 group-hover:rotate-12"
                  />

                  <span className="text-sm font-medium text-slate-700 transition-colors duration-300 group-hover:text-[#11224D] sm:text-base">
                    {item}
                  </span>

                </div>

              ))}

            </div>


            <div className="mt-9 grid gap-4 sm:grid-cols-3">

              {features.map((feature) => {
                const Icon = feature.icon;

                return (
                  <div
                    key={feature.title}
                    className="group cursor-pointer rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-[#0492E8]/20 hover:bg-[#0492E8] hover:shadow-xl hover:shadow-[#0492E8]/20"
                  >

                    <div className="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#0492E8]/10 transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-white/10">

                      <Icon className="h-5 w-5 text-[#11224D] transition-colors duration-300 group-hover:text-white" />

                    </div>

                    <h3 className="text-sm font-bold text-slate-900 transition-colors duration-300 group-hover:text-white">
                      {feature.title}
                    </h3>

                    <p className="mt-2 text-xs leading-5 text-slate-500 transition-colors duration-300 group-hover:text-white/70">
                      {feature.text}
                    </p>

                  </div>
                );
              })}

            </div>


            <div className="mt-8 inline-flex cursor-pointer items-center gap-3 text-sm font-bold text-[#11224D]">

              <span className="relative">
                Trusted Moving Professionals

                <span className="absolute -bottom-1 left-0 h-[2px] w-0 bg-[#0492E8] transition-all duration-500 hover:w-full" />
              </span>

              <div className="flex h-9 w-9 items-center justify-center rounded-full bg-[#0492E8] text-[#102a4c] transition-all duration-500 hover:rotate-45 hover:scale-110 hover:bg-blue-600">
                <ArrowUpRight className="h-4 w-4" />
              </div>

            </div>

          </div>

        </div>

      </div>
    </section>
  );
}