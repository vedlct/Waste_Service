"use client";

import { Building2, ChevronRight, MapPin, X } from "lucide-react";
import { useState } from "react";

const areas = [
  {
    division: "Central London",
    places: ["Westminster", "Camden", "City of London", "Islington"],
  },
  {
    division: "North London",
    places: ["Barnet", "Enfield", "Haringey", "Hackney"],
  },
  {
    division: "South London",
    places: ["Croydon", "Lambeth", "Lewisham", "Southwark"],
  },
  {
    division: "East London",
    places: ["Newham", "Tower Hamlets", "Barking & Dagenham", "Redbridge"],
  },
  {
    division: "West London",
    places: ["Hammersmith & Fulham", "Ealing", "Hounslow", "Richmond"],
  },
  {
    division: "Greater London",
    places: ["Kingston", "Bromley", "Sutton", "Waltham Forest"],
  },
];

export default function AreasWeCoverSection() {
  const [selectedPlace, setSelectedPlace] = useState("Westminster");

  const handlePlaceClick = (place) => {
    setSelectedPlace(place);
  };

  const mapUrl = `https://www.google.com/maps?q=${encodeURIComponent(
    selectedPlace + ", London, UK"
  )}&output=embed`;

  return (
    <div className="bg-linear-to-b from-[#E9F4FC] to-white">

      <div className="container mx-auto flex max-w-7xl flex-col items-center justify-center gap-4 px-4 py-8 text-center sm:px-6 sm:py-10 md:gap-5 lg:px-8 lg:py-14">

        <span className="inline-block rounded-full bg-white px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-blue-600 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-100 sm:text-xs">
          Popular Areas
        </span>

        <h2 className="text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-4xl md:text-4xl">
          Areas We Cover
        </h2>

        <p className="max-w-2xl text-sm leading-relaxed text-slate-500 sm:text-base">
          We provide reliable rubbish removal services across London.
          Click on any area below to view its location on the map.
        </p>

        <div className="mt-4 grid w-full grid-cols-1 gap-4 text-left sm:mt-6 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 xl:grid-cols-4">

          {areas.map((area) => (
            <div
              key={area.division}
              className="group flex cursor-pointer flex-col rounded-xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:bg-blue-50/40 hover:shadow-lg hover:shadow-blue-100/50 sm:p-6"
            >

              <div className="mb-4 flex items-center gap-3">

                <span className="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-blue-600 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                  <Building2 className="h-4 w-4 text-[#102a4c]" />
                </span>

                <h3 className="text-[15px] font-bold text-slate-900 transition-colors duration-200 group-hover:text-blue-600 sm:text-base">
                  {area.division}
                </h3>

              </div>

              <ul className="flex flex-1 flex-col gap-2.5">

                {area.places.map((place) => {

                  const isSelected = selectedPlace === place;

                  return (
                    <li key={place}>

                      <button
                        type="button"
                        onClick={() => handlePlaceClick(place)}
                        className={`group/item -mx-1.5 flex w-[calc(100%+12px)] items-center gap-1.5 rounded-md px-1.5 py-1 text-left text-sm transition-all duration-300 ${
                          isSelected
                            ? "translate-x-1 bg-blue-100 font-semibold text-blue-600"
                            : "text-slate-600 hover:translate-x-1 hover:bg-blue-50 hover:text-blue-600"
                        }`}
                      >

                        <ChevronRight
                          className={`h-3.5 w-3.5 flex-shrink-0 transition-all duration-300 ${
                            isSelected
                              ? "translate-x-0.5 text-blue-500"
                              : "text-slate-400 group-hover/item:translate-x-0.5 group-hover/item:text-blue-500"
                          }`}
                        />

                        <span className="flex-1">
                          {place}
                        </span>

                        {isSelected && (
                          <MapPin className="h-3.5 w-3.5 text-blue-600" />
                        )}

                      </button>

                    </li>
                  );
                })}

              </ul>
            </div>
          ))}

        </div>

        <div className="mt-6 w-full overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-xl shadow-blue-100/40">

          <div className="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">

            <div className="flex items-center gap-3">

              <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-600">
                <MapPin className="h-5 w-5 text-[#102a4c]" />
              </div>

              <div>
                <p className="text-xs font-semibold uppercase tracking-wider text-blue-600">
                  Selected Area
                </p>

                <h3 className="text-lg font-bold text-slate-900 sm:text-xl">
                  {selectedPlace}
                </h3>
              </div>

            </div>

            <div className="flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-600">
              <MapPin className="h-4 w-4" />
              London, UK
            </div>

          </div>

          <div className="relative h-[300px] w-full overflow-hidden sm:h-[400px] lg:h-[450px]">

            <iframe
              key={selectedPlace}
              src={mapUrl}
              width="100%"
              height="100%"
              style={{ border: 0 }}
              loading="lazy"
              allowFullScreen
              referrerPolicy="no-referrer-when-downgrade"
              title={`${selectedPlace} Google Map`}
              className="h-full w-full"
            />

          </div>

        </div>

      </div>

    </div>
  );
}