import { MapPin } from "lucide-react";

const legendItems = [
  { label: "Fully Covered", dotClass: "bg-blue-600" },
  { label: "Partially Covered", dotClass: "bg-blue-400" },
  { label: "Coming Soon", dotClass: "bg-blue-100" },
];

export default function AreaIntro() {
  return (
    <div className='bg-linear-to-b from-[#FFFFFF] to-[#E9F4FC]'>
      <div className="container mx-auto flex max-w-7xl flex-col items-center justify-center gap-4 px-4 pb-8 pt-28 text-center sm:px-6 sm:pb-10 sm:pt-32 md:gap-5 lg:px-8 lg:pb-14 lg:pt-36">
        <span className="inline-block rounded-full bg-blue-50 px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-blue-600 sm:text-xs">
        Our Coverage Map
      </span>
      <h2 className="text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-4xl md:text-4xl lg:text-4xl">
        Where We
        <span className="block text-blue-600">Operate</span>
      </h2>

      <p className="max-w-md text-sm leading-relaxed text-slate-500 sm:max-w-lg sm:text-base md:max-w-xl lg:text-lg">
        Our services are available across multiple regions. Click on any area
        to see more details about our availability there.
      </p>


      <ul className="mt-2 flex flex-col items-start gap-4 sm:mt-4 sm:gap-5 md:flex-row md:flex-wrap md:justify-center md:gap-x-8 md:gap-y-4">
        {legendItems.map((item) => (
          <li
            key={item.label}
            className="flex items-center gap-3 text-sm font-semibold text-slate-900 sm:text-base"
          >
            <span
              className={`h-3 w-3 flex-shrink-0 rounded-full ${item.dotClass}`}
            />
            {item.label}
          </li>
        ))}

        <li className="flex items-center gap-3 text-sm font-semibold text-slate-900 sm:text-base">
          <MapPin className="h-[18px] w-[18px] flex-shrink-0 text-blue-600" />
          Major City
        </li>
       </ul>
      </div>
    </div>
  );
}

