import Image from 'next/image';
import Link from 'next/link';
import { ArrowRight, ChevronDown } from 'lucide-react';
import { extraBlocks, faqJsonLd, paragraphs, safeHref } from '@/lib/services';
import { jsonLdString } from '@/lib/seo';

// Admin-managed additions to a service page: extra content blocks, the service's FAQs and
// related services. Renders nothing when the service has none, or the backend is down.
export default function ServiceExtras({ service }) {
  const blocks = extraBlocks(service);
  const faqs = service?.faqs ?? [];
  const related = service?.related ?? [];
  const faqData = faqJsonLd(faqs);

  if (blocks.length === 0 && faqs.length === 0 && related.length === 0) return null;

  return (
    <>
      {blocks.map((block, index) => (
        <ContentBlock key={block.key || `${block.component}-${index}`} block={block} shaded={index % 2 === 0} />
      ))}

      {faqs.length > 0 && (
        <section className="bg-white" aria-labelledby="service-faqs">
          <div className="mx-auto w-full max-w-4xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <Eyebrow>Questions</Eyebrow>
            <h2 id="service-faqs" className="mt-3 text-3xl font-bold tracking-tight text-[#102a4c] sm:text-4xl">
              {service.name} FAQs
            </h2>
            <div className="mt-8 divide-y divide-sky-100 rounded-3xl border border-sky-100">
              {faqs.map((faq) => (
                <Faq key={faq.id ?? faq.question} question={faq.question} answer={faq.answer} />
              ))}
            </div>
          </div>
          {faqData && <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: jsonLdString(faqData) }} />}
        </section>
      )}

      {related.length > 0 && (
        <section className="bg-sky-50" aria-labelledby="related-services">
          <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <Eyebrow>More services</Eyebrow>
            <h2 id="related-services" className="mt-3 text-3xl font-bold tracking-tight text-[#102a4c] sm:text-4xl">
              Related services
            </h2>
            <div className="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
              {related.map((item) => (
                <RelatedCard key={item.slug} service={item} />
              ))}
            </div>
          </div>
        </section>
      )}
    </>
  );
}

function Eyebrow({ children }) {
  return (
    <p className="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[0.18em] text-[#0398E9] sm:text-sm">
      <span className="h-px w-8 bg-[#ffd126]" aria-hidden="true" />
      {children}
    </p>
  );
}

function Faq({ question, answer }) {
  return (
    <details className="group px-5 py-4 sm:px-6">
      <summary className="flex cursor-pointer list-none items-center justify-between gap-4 font-bold text-[#102a4c]">
        {question}
        <ChevronDown className="size-5 shrink-0 text-[#0398E9] transition group-open:rotate-180" aria-hidden="true" />
      </summary>
      <div className="mt-3 space-y-3 text-sm leading-7 text-[#102a4c]/78 sm:text-base">
        {paragraphs(answer).map((text) => <p key={text}>{text}</p>)}
      </div>
    </details>
  );
}

function MediaImage({ image, className, sizes }) {
  if (!image?.url) return null;

  return (
    <Image
      src={image.url}
      alt={image.alt || ''}
      fill
      sizes={sizes}
      unoptimized={!image.url.startsWith('/')}
      className={className}
    />
  );
}

function ContentBlock({ block, shaded }) {
  const items = block.items ?? [];
  const body = paragraphs(block.body);

  if (block.component === 'cta') {
    const href = safeHref(items[0]?.url) ?? '/#prices';

    return (
      <section className="bg-[#102a4c]">
        <div className="mx-auto flex w-full max-w-7xl flex-col items-start gap-6 px-4 py-14 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
          <div className="max-w-2xl text-white">
            {block.heading && <h2 className="text-2xl font-bold sm:text-3xl">{block.heading}</h2>}
            {body.map((text) => <p key={text} className="mt-3 text-white/80">{text}</p>)}
          </div>
          <Link href={href} className="inline-flex min-h-12 items-center gap-2 rounded-full bg-[#ffd126] px-6 py-3 text-sm font-extrabold text-[#102a4c] transition hover:bg-white">
            {items[0]?.title || 'Check prices & book'}
            <ArrowRight className="size-4" aria-hidden="true" />
          </Link>
        </div>
      </section>
    );
  }

  return (
    <section className={shaded ? 'bg-[#f7f8f4]' : 'bg-white'}>
      <div className="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        <div className={block.image?.url ? 'grid gap-10 lg:grid-cols-2 lg:items-center' : 'max-w-3xl'}>
          <div>
            {block.eyebrow && <Eyebrow>{block.eyebrow}</Eyebrow>}
            {block.heading && <h2 className="mt-3 text-3xl font-bold tracking-tight text-[#102a4c] sm:text-4xl">{block.heading}</h2>}
            {block.subheading && <p className="mt-3 text-lg font-semibold text-[#102a4c]/80">{block.subheading}</p>}
            <div className="mt-5 space-y-4 leading-7 text-[#102a4c]/78">
              {body.map((text) => <p key={text}>{text}</p>)}
            </div>
          </div>
          {block.image?.url && (
            <div className="relative aspect-[4/3] overflow-hidden rounded-3xl">
              <MediaImage image={block.image} sizes="(min-width: 1024px) 50vw, 100vw" className="object-cover" />
            </div>
          )}
        </div>

        {items.length > 0 && <BlockItems component={block.component} items={items} />}
      </div>
    </section>
  );
}

function BlockItems({ component, items }) {
  if (component === 'faq') {
    return (
      <div className="mt-8 max-w-4xl divide-y divide-sky-100 rounded-3xl border border-sky-100 bg-white">
        {items.map((item, index) => (
          <Faq key={`${item.title}-${index}`} question={item.title} answer={item.body} />
        ))}
      </div>
    );
  }

  if (component === 'gallery') {
    return (
      <div className="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-3">
        {items.filter((item) => item.image?.url).map((item, index) => (
          <figure key={`${item.image.url}-${index}`}>
            <div className="relative aspect-square overflow-hidden rounded-2xl">
              <MediaImage image={item.image} sizes="(min-width: 1024px) 33vw, 50vw" className="object-cover" />
            </div>
            {item.title && <figcaption className="mt-2 text-sm font-semibold text-[#102a4c]">{item.title}</figcaption>}
          </figure>
        ))}
      </div>
    );
  }

  const numbered = component === 'steps';

  return (
    <ul className="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
      {items.map((item, index) => {
        const href = safeHref(item.url);

        return (
          <li key={`${item.title}-${index}`} className="rounded-3xl border border-sky-100 bg-white p-6 shadow-sm">
            {item.image?.url && (
              <div className="relative mb-4 aspect-[4/3] overflow-hidden rounded-2xl">
                <MediaImage image={item.image} sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw" className="object-cover" />
              </div>
            )}
            {numbered && (
              <span className="mb-3 grid size-9 place-items-center rounded-full bg-[#ffd126] text-sm font-extrabold text-[#102a4c]">{index + 1}</span>
            )}
            <h3 className="text-lg font-bold text-[#102a4c]">{item.title}</h3>
            {item.subtitle && <p className="mt-1 text-sm font-semibold text-[#0398E9]">{item.subtitle}</p>}
            {paragraphs(item.body).map((text) => <p key={text} className="mt-2 text-sm leading-6 text-[#102a4c]/78">{text}</p>)}
            {href && (
              <Link href={href} className="mt-4 inline-flex items-center gap-1 text-sm font-bold text-[#0398E9] hover:underline">
                Learn more <ArrowRight className="size-4" aria-hidden="true" />
              </Link>
            )}
          </li>
        );
      })}
    </ul>
  );
}

function RelatedCard({ service }) {
  const href = safeHref(service.route_path) ?? '/';

  return (
    <Link href={href} className="group overflow-hidden rounded-3xl border border-sky-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
      {service.hero_image?.url && (
        <div className="relative aspect-[16/10]">
          <MediaImage image={service.hero_image} sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw" className="object-cover" />
        </div>
      )}
      <div className="p-6">
        <h3 className="text-lg font-bold text-[#102a4c]">{service.short_name || service.name}</h3>
        {service.summary && <p className="mt-2 text-sm leading-6 text-[#102a4c]/78">{service.summary}</p>}
        <span className="mt-4 inline-flex items-center gap-1 text-sm font-bold text-[#0398E9]">
          View service <ArrowRight className="size-4 transition group-hover:translate-x-1" aria-hidden="true" />
        </span>
      </div>
    </Link>
  );
}
