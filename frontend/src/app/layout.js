import { Geist, Geist_Mono } from "next/font/google";
import "./globals.css";
import SiteChrome from "@/components/layout/SiteChrome";
import { getLocalBusinessJsonLd, jsonLdString, siteMetadata } from "@/lib/seo";
import { getSiteContact } from "@/lib/site";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
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
      className={`${geistSans.variable} ${geistMono.variable} h-full antialiased`}
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
