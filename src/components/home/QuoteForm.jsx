import Link from 'next/link'
import { ArrowRight, Camera, Clock3, MessageCircle, Phone, ShieldCheck } from 'lucide-react'

const steps = [
  { icon: MessageCircle, title: 'Tell us what needs clearing', text: 'Share the type of waste, your location and the collection date you have in mind.' },
  { icon: Camera, title: 'Add photos if helpful', text: 'Pictures help our team understand the volume and provide clearer guidance before collection.' },
  { icon: Clock3, title: 'Receive your next step', text: 'We will review the details and explain the most practical collection option for your job.' },
]

export default function QuoteForm() {
  return (
    <section id='form' className='relative overflow-hidden bg-linear-to-br from-[#B9E8FA] via-[#8FD8F5] to-[#D9F2FC] py-16 text-[#102a4c] sm:py-20 lg:py-28'>
      <div aria-hidden='true' className='absolute -left-28 -top-32 size-80 rounded-full border-[58px] border-white/[0.07] sm:size-96' />
      <div aria-hidden='true' className='absolute -bottom-32 -right-20 size-80 rounded-full bg-[#F4B942]/20 blur-2xl' />
      <div aria-hidden='true' className='absolute right-[12%] top-10 hidden grid-cols-5 gap-3 opacity-25 lg:grid'>
        {Array.from({ length: 20 }).map((_, index) => <span key={index} className='size-1.5 rounded-full bg-white' />)}
      </div>

      <div className='relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8'>
        <div className='mx-auto max-w-5xl text-center'>
          <span className='inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-[0.22em] shadow-sm backdrop-blur' style={{ color: '#102a4c' }}>
            Need a little guidance?
          </span>

          <h2 className='mt-7 text-3xl font-black leading-[1.02] tracking-[-0.05em] sm:text-4xl lg:text-4xl'>
            You don&apos;t need to work out
            <span className='block text-white/90'>every detail on your own.</span>
          </h2>

          <p className='mx-auto mt-6 max-w-3xl text-base leading-7 text-[#102a4c]/75 sm:text-lg'>
            Tell our team about your clearance and we&apos;ll help you understand the options, likely collection size and what happens next. The full enquiry form is available on our contact page.
          </p>

          <div className='mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row'>
            <Link href='/contactUs' className='group inline-flex w-full items-center justify-center gap-3 rounded-full bg-white px-6 py-4 text-sm font-black text-[#11224D] shadow-xl transition-all duration-300 hover:-translate-y-1 hover:bg-[#F4B942] hover:shadow-2xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white sm:w-auto sm:text-base'>
              Open the contact form
              <ArrowRight aria-hidden='true' className='size-5 transition-transform duration-300 group-hover:translate-x-1' />
            </Link>

            <a href='tel:02082266477' className='group inline-flex w-full items-center justify-center gap-3 rounded-full border border-white/30 bg-white/10 px-6 py-4 text-sm font-black text-[#102a4c] backdrop-blur transition-all duration-300 hover:-translate-y-1 hover:border-white hover:bg-white/20 hover:shadow-xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white sm:w-auto sm:text-base'>
              <Phone aria-hidden='true' className='size-5 transition-transform duration-300 group-hover:rotate-6 group-hover:scale-110' />
              020 8226 6477
            </a>
          </div>
        </div>

        <div className='mt-12 grid grid-cols-1 gap-4 sm:mt-14 md:grid-cols-3'>
          {steps.map((step, index) => {
            const Icon = step.icon

            return (
              <article key={step.title} className='group relative overflow-hidden rounded-[1.75rem] border border-white/55 bg-[#0492E8]/15 p-5 text-left shadow-sm backdrop-blur-md transition-all duration-500 hover:-translate-y-2 hover:border-white/80 hover:bg-[#0492E8]/25 hover:shadow-2xl sm:p-6'>
                <div className='flex items-start justify-between gap-4'>
                  <span className='flex size-12 items-center justify-center rounded-2xl bg-white text-[#11224D] transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 group-hover:bg-[#F4B942]'>
                    <Icon aria-hidden='true' className='size-6' />
                  </span>
                  <span className='text-xs font-black tracking-[0.18em] text-[#102a4c]/35'>{String(index + 1).padStart(2, '0')}</span>
                </div>
                <h3 className='mt-7 text-xl font-black text-[#102a4c] sm:text-2xl'>{step.title}</h3>
                <p className='mt-3 text-sm leading-6 text-[#102a4c]/65 sm:text-base'>{step.text}</p>
                <span className='absolute inset-x-6 bottom-0 h-1 origin-left scale-x-0 rounded-full bg-[#F4B942] transition-transform duration-500 group-hover:scale-x-100' />
              </article>
            )
          })}
        </div>

        <div className='mt-8 flex flex-col gap-4 border-t border-white/15 pt-6 text-sm text-[#102a4c]/65 sm:flex-row sm:items-center sm:justify-between'>
          <p className='inline-flex items-center gap-2'>
            <ShieldCheck aria-hidden='true' className='size-5 shrink-0 text-[#F4C95D]' />
            Your details are used only to respond to your clearance enquiry.
          </p>
          <p className='font-semibold text-[#102a4c]'>Friendly support Â· Clear options Â· No pressure</p>
        </div>
      </div>
    </section>
  )
}
