import { Barlow_Condensed, Inter } from "next/font/google";
import "./globals.css";
import SiteChrome from "@/components/layout/SiteChrome";
import { getLocalBusinessJsonLd, jsonLdString, siteMetadata } from "@/lib/seo";
import { getSiteContact } from "@/lib/site";

// Inter for body copy; Barlow Condensed for headings, matching the bold condensed type on
// the company's vans and flyers.
const inter = Inter({
  variable: "--font-inter",
  subsets: ["latin"],
});

const barlow = Barlow_Condensed({
  variable: "--font-barlow",
  subsets: ["latin"],
  weight: ["600", "700", "800"],
});

// Site-wide defaults and verification codes; each page overrides the title, description and
// share card from the admin's SEO fields.
export function generateMetadata() {
  return siteMetadata();
}

export default async function RootLayout({ children }) {
  const [contact, business] = await Promise.all([getSiteContact(), getLocalBusinessJsonLd()]);

  return (
    <html
      lang="en"
      className={`${inter.variable} ${barlow.variable} h-full antialiased`}
    >
      <body suppressHydrationWarning>
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: jsonLdString(business) }}
        />
        <SiteChrome contact={contact}>
          {children}
        </SiteChrome>
      </body>
    </html>
  );
}
