import React, { useEffect, useRef, useState } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const SkewButton = ({ children, className = "", color = "bg-[#0EA5E9]", hideArrow = false, ...props }) => (
  <button
    {...props}
    className={`skew-button ${color} ${className}`}
  >
    <div className="skew-content font-black uppercase text-white">
      <span>{children}</span>
      {!hideArrow && (
        <span>
          <svg viewBox="0 0 66 43" xmlns="http://www.w3.org/2000/svg">
            <g stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
              <path className="one" d="M40.1543933,3.89485454 L43.9763149,0.139296592 C44.1708311,-0.0518420739 44.4826329,-0.0518571125 44.6771675,0.139262789 L65.6916134,20.7848311 C66.0855801,21.1718824 66.0911863,21.8050225 65.704135,22.1989893 C65.7000188,22.2031791 65.6958657,22.2073326 65.6916762,22.2114492 L44.677098,42.8607841 C44.4825957,43.0519059 44.1708242,43.0519358 43.9762853,42.8608513 L40.1545186,39.1069479 C39.9575152,38.9134427 39.9546793,38.5968729 40.1481845,38.3998695 C40.1502893,38.3977268 40.1524132,38.395603 40.1545562,38.3934985 L56.9937789,21.8567812 C57.1908028,21.6632968 57.193672,21.3467273 57.0001876,21.1497035 C56.9980647,21.1475418 56.9959223,21.1453995 56.9937605,21.1432767 L40.1545208,4.60825197 C39.9574869,4.41477773 39.9546013,4.09820839 40.1480756,3.90117456 C40.1501626,3.89904911 40.1522686,3.89694235 40.1543933,3.89485454 Z" fill="#FFFFFF"></path>
              <path className="two" d="M20.1543933,3.89485454 L23.9763149,0.139296592 C24.1708311,-0.0518420739 24.4826329,-0.0518571125 24.6771675,0.139262789 L45.6916134,20.7848311 C46.0855801,21.1718824 46.0911863,21.8050225 45.704135,22.1989893 C45.7000188,22.2031791 45.6958657,22.2073326 45.6916762,22.2114492 L24.677098,42.8607841 C24.4825957,43.0519059 24.1708242,43.0519358 23.9762853,42.8608513 L20.1545186,39.1069479 C19.9575152,38.9134427 19.9546793,38.5968729 20.1481845,38.3998695 C20.1502893,38.3977268 20.1524132,38.395603 20.1545562,38.3934985 L36.9937789,21.8567812 C37.1908028,21.6632968 37.193672,21.3467273 37.0001876,21.1497035 C36.9980647,21.1475418 36.9959223,21.1453995 36.9937605,21.1432767 L20.1545208,4.60825197 C19.9574869,4.41477773 19.9546013,4.09820839 20.1480756,3.90117456 C20.1501626,3.89904911 20.1522686,3.89694235 20.1543933,3.89485454 Z" fill="#FFFFFF"></path>
              <path className="three" d="M0.154393339,3.89485454 L3.97631488,0.139296592 C4.17083111,-0.0518420739 4.48263286,-0.0518571125 4.67716753,0.139262789 L25.6916134,20.7848311 C26.0855801,21.1718824 26.0911863,21.8050225 25.704135,22.1989893 C25.7000188,22.2031791 25.6958657,22.2073326 25.6916762,22.2114492 L4.67709797,42.8607841 C4.48259567,43.0519059 4.17082418,43.0519358 3.97628526,42.8608513 L0.154518591,39.1069479 C-0.0424848215,38.9134427 -0.0453206733,38.5968729 0.148184538,38.3998695 C0.150289256,38.3977268 0.152413239,38.395603 0.154556228,38.3934985 L16.9937789,21.8567812 C17.1908028,21.6632968 17.193672,21.3467273 17.0001876,21.1497035 C16.9980647,21.1475418 16.9959223,21.1453995 16.9937605,21.1432767 L0.15452076,4.60825197 C-0.0425130651,4.41477773 -0.0453986756,4.09820839 0.148075568,3.90117456 C0.150162624,3.89904911 0.152268631,3.89694235 0.154393339,3.89485454 Z" fill="#FFFFFF"></path>
            </g>
          </svg>
        </span>
      )}
    </div>
  </button>
);

const RetroButton = ({ children, className = "", primary = false, ...props }) => (
  <button
    {...props}
    className={`
      px-8 py-3 font-black text-xl border-4 border-black 
      ${primary ? 'bg-[#FF6B00] text-white' : 'bg-white text-black'} 
      shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] 
      hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] 
      active:translate-x-[4px] active:translate-y-[4px] active:shadow-none 
      transition-all cursor-pointer inline-block pointer-events-auto
      ${className}
    `}
  >
    {children}
  </button>
);

const ScrollReveal = ({ children, direction = "up" }) => {
  const elementRef = useRef(null);

  useEffect(() => {
    const el = elementRef.current;
    let xMove = 0, yMove = 0;

    if (direction === "up") yMove = 100;
    if (direction === "down") yMove = -100;
    if (direction === "left") xMove = 100;
    if (direction === "right") xMove = -100;

    gsap.fromTo(el,
      { opacity: 0, x: xMove, y: yMove },
      {
        opacity: 1, x: 0, y: 0,
        duration: 1,
        ease: "power4.out",
        scrollTrigger: {
          trigger: el,
          start: "top 90%",
          end: "bottom 10%",
          toggleActions: "play reverse play reverse"
        }
      }
    );
  }, [direction]);

  return <div ref={elementRef}>{children}</div>;
};

export default function App() {
  const [scrollProgress, setScrollProgress] = useState(0);
  const [barColor, setBarColor] = useState('#FF6B00');
  const [showSplash, setShowSplash] = useState(true);
  const splashRef = useRef(null);
  const splashTextRef = useRef(null);

  useEffect(() => {
    const ctx = gsap.context(() => {
        const words = splashTextRef.current.querySelectorAll('.word');
        const isMobile = window.innerWidth < 768;
        
        const tl = gsap.timeline({
            onComplete: () => {
                gsap.to(splashRef.current, {
                    y: "100%",
                    duration: 1,
                    ease: "power4.inOut",
                    onComplete: () => setShowSplash(false)
                });
            }
        });

        tl.fromTo(words, 
            { opacity: 0, y: 50 },
            { 
              opacity: 1, 
              y: 0, 
              duration: 0.8, 
              stagger: isMobile ? 0.8 : 0.4, 
              ease: "back.out(1.7)" 
            }
        ).to({}, { duration: 0.5 });
    });
    return () => ctx.revert();
  }, []);

  useEffect(() => {
    const handleScroll = () => {
      const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
      const progress = (window.scrollY / totalHeight) * 100;
      setScrollProgress(progress);

      if (progress > 75) setBarColor('#FF6B00');
      else if (progress > 50) setBarColor('#0EA5E9');
      else if (progress > 25) setBarColor('#10B981');
      else setBarColor('#FF6B00');
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <div className="font-poppins bg-[#F4E4BC] selection:bg-black selection:text-white overflow-x-hidden">
      {showSplash && (
        <div 
          ref={splashRef}
          className="fixed inset-0 z-[1000] bg-black flex items-center justify-center overflow-hidden"
        >
          <div ref={splashTextRef} className="flex flex-col md:flex-row items-center gap-4 md:gap-8">
            <span className="word text-white text-5xl md:text-8xl font-black italic">Hello,</span>
            <span className="word text-white text-5xl md:text-8xl font-black italic">We Are</span>
            <span className="word text-[#E65100] text-5xl md:text-8xl font-black italic">Griya Chandra.</span>
          </div>
        </div>
      )}

      {/* Scroll Zipper Logic */}
      <div className="fixed right-6 top-1/2 -translate-y-1/2 w-8 h-[70vh] z-50 hidden md:block group">
          <div className="absolute inset-0 bg-black/10 rounded-full border-4 border-black"></div>
          <div 
            className="absolute left-1/2 -translate-x-1/2 w-12 h-20 bg-[#FFD100] border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center cursor-grab active:cursor-grabbing z-20"
            style={{ 
              top: `${scrollProgress}%`,
              transform: 'translateY(-50%)'
            }}
          >
              <div className="w-2 h-6 bg-black/20 rounded-full border-2 border-black/40"></div>
              <div className="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-white/20 via-transparent to-black/20 pointer-events-none"></div>
          </div>
          <div 
            className="absolute top-0 left-0 w-full bg-gray-500/40 transition-all duration-300"
            style={{ height: `${scrollProgress}%` }}
          >
              <div className="absolute inset-0 opacity-10 bg-[radial-gradient(circle,#000_1px,transparent_1px)] [background-size:8px_8px]"></div>
          </div>
      </div>

      <div className="fixed top-0 left-0 w-full h-3 z-[90] border-b-2 border-black bg-white/20 backdrop-blur-sm">
        <div 
          className="h-full transition-all duration-300 ease-out"
          style={{ width: `${scrollProgress}%`, backgroundColor: barColor }}
        />
      </div>

      <nav className="fixed w-full z-[80] bg-gray-500/50 backdrop-blur-md border-b-4 border-black">
        <div className="max-w-7xl mx-auto px-4 md:px-12 py-4 md:py-6 flex justify-between items-center relative">
          <div className="flex items-center">
            <a href="/">
              <SkewButton color="bg-[#FF6B00]" hideArrow className="!h-9 md:!h-12 !px-2 md:!px-4 !border-2 md:!border-4">
                <span className="text-lg md:text-2xl font-black">GC</span>
              </SkewButton>
            </a>
          </div>

          <div className="absolute left-1/2 -translate-x-1/2 flex items-center justify-center">
            <SkewButton color="bg-[#0EA5E9]" className="!h-8 md:!h-12 !px-4 md:!px-8 !border-2 md:!border-4 scale-90 md:scale-100">
              <span className="text-[10px] md:text-base">About</span>
            </SkewButton>
          </div>

          <div className="flex items-center">
            <a href="/login">
              <SkewButton color="bg-[#10B981]" className="!h-8 md:!h-12 !px-4 md:!px-8 !border-2 md:!border-4 scale-90 md:scale-100">
                <span className="text-[10px] md:text-base">Masuk</span>
              </SkewButton>
            </a>
          </div>
        </div>
      </nav>

      <main id="hero" className="relative z-10 pt-48 max-w-7xl mx-auto px-6 lg:px-20 mb-40">
        <section className="min-h-[80vh] flex flex-col justify-center">
          <ScrollReveal direction="left">
            <div className="inline-block px-4 py-1 bg-[#10B981] border-4 border-black font-black uppercase text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] mb-8 text-white">
              Berdiri Sejak - 2014
            </div>
            <h1 className="text-7xl md:text-9xl font-black leading-[0.85] uppercase tracking-tighter mb-10">
              <span className="text-[#10B981] italic text-stroke-black">Griya</span><span className="text-white text-stroke-black"> -</span> <br /> <span className="text-[#FF6B00] italic text-stroke-black">Chandra</span><span className="text-[#10B981] text-stroke-black">.</span>
            </h1>
            <p className="text-2xl font-bold leading-tight max-w-xl mb-12 border-l-8 border-[#FF6B00] pl-6">
              <span className="text-[#10B981]">Indekost dengan konsep</span> <span className="text-[#FF6B00]">semi apartemen</span>
            </p>
          </ScrollReveal>
        </section>
      </main>

      <div id="security" className="w-full bg-[#10B981] border-t-4 border-black relative py-32 z-10">
        <div className="absolute inset-0 opacity-10 bg-[repeating-linear-gradient(45deg,#000,#000_10px,transparent_10px,transparent_20px)] animate-bg-diagonal"></div>
        <div className="max-w-7xl mx-auto px-6 lg:px-20 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10">
          <ScrollReveal direction="right">
            <div className="aspect-square bg-[#0EA5E9] border-4 border-black shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center relative">
              <span className="text-[180px] select-none">🔐</span>
              <div className="absolute -top-6 -right-6 bg-white border-4 border-black p-4 font-black rotate-3 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                AMAN 24 JAM
              </div>
            </div>
          </ScrollReveal>
          <ScrollReveal direction="left">
            <h2 className="text-6xl md:text-7xl font-black uppercase mb-8 leading-none text-black text-stroke-black">Keamanan <br /> Berbasis <br /> <span className="text-white">Teknologi.</span></h2>
            <p className="text-xl font-bold leading-relaxed text-black">
              Kami menggabungkan kenyamanan desain retro dengan sistem keamanan tercanggih. Smart lock, CCTV di setiap sudut, dan sistem monitoring real-time.
            </p>
          </ScrollReveal>
        </div>
      </div>

      <div id="rooms" className="w-full bg-[#0EA5E9] border-t-4 border-black relative py-32 z-10">
        <div className="absolute inset-0 opacity-20 bg-[radial-gradient(#000_2px,transparent_2px)] [background-size:24px_24px] animate-bg-dots"></div>
        <div className="max-w-7xl mx-auto px-6 lg:px-20 relative z-10">
          <ScrollReveal>
            <div className="text-center mb-20">
              <h2 className="text-7xl font-black uppercase tracking-tighter text-white text-stroke-black">Pilihan Kamar</h2>
            </div>
          </ScrollReveal>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-10">
            <ScrollReveal direction="right">
              <div className="card-perspective h-[450px]">
                <div className="card-inner">
                  <div className="card-front p-10 border-4 border-black bg-[#FFD100] shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
                    <div className="text-5xl mb-6">❄️</div>
                    <h3 className="text-5xl font-black uppercase mb-4 text-stroke-black">Tipe AC</h3>
                    <p className="font-bold text-lg mb-8">Kenyamanan maksimal dengan pendingin udara dan fasilitas lengkap.</p>
                    <div className="mt-auto">
                      <RetroButton className="w-full">Detail Kamar</RetroButton>
                    </div>
                  </div>
                  <div className="card-back p-10 border-4 border-black bg-black text-white shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
                    <h3 className="text-3xl font-black uppercase mb-6 text-[#FFD100]">Fasilitas Utama</h3>
                    <ul className="space-y-4 font-bold text-lg mb-8">
                      <li>✓ AC Daikin 1/2 PK</li>
                      <li>✓ Smart TV 32 Inch</li>
                      <li>✓ Kamar Mandi Dalam</li>
                      <li>✓ Meja & Kursi Kerja</li>
                    </ul>
                    <div className="mt-auto">
                      <RetroButton className="w-full !bg-[#FF6B00] !text-white">Pilih Kamar Ini</RetroButton>
                    </div>
                  </div>
                </div>
              </div>
            </ScrollReveal>

            <ScrollReveal direction="left">
              <div className="card-perspective h-[450px]">
                <div className="card-inner">
                  <div className="card-front p-10 border-4 border-black bg-white shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
                    <div className="text-5xl mb-6">🌿</div>
                    <h3 className="text-5xl font-black uppercase mb-4 text-[#10B981] text-stroke-black">Tipe Non-AC</h3>
                    <p className="font-bold text-lg mb-8 text-slate-600">Kesegaran alami dengan sirkulasi udara yang dirancang khusus.</p>
                    <div className="mt-auto">
                      <RetroButton className="w-full">Detail Kamar</RetroButton>
                    </div>
                  </div>
                  <div className="card-back p-10 border-4 border-black bg-[#10B981] text-white shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
                    <h3 className="text-3xl font-black uppercase mb-6 text-black">Fasilitas Utama</h3>
                    <ul className="space-y-4 font-bold text-lg mb-8 text-black">
                      <li>✓ Exhaust Fan Turbo</li>
                      <li>✓ Kasur Springbed</li>
                      <li>✓ Lemari Pakaian</li>
                      <li>✓ Free Wi-Fi 5G</li>
                    </ul>
                    <div className="mt-auto">
                      <RetroButton className="w-full !bg-white !text-black">Pilih Kamar Ini</RetroButton>
                    </div>
                  </div>
                </div>
              </div>
            </ScrollReveal>
          </div>
        </div>
      </div>

      <div id="cta" className="w-full bg-[#FF6B00] border-t-4 border-black relative py-40 z-10">
        <div className="absolute inset-0 opacity-10 bg-[repeating-linear-gradient(0deg,transparent,transparent_20px,#000_20px,#000_23px)] animate-bg-vertical"></div>
        <div className="max-w-7xl mx-auto px-6 lg:px-20 relative z-10 text-center">
          <ScrollReveal direction="up">
            <div className="bg-white border-4 border-black p-16 md:p-24 shadow-[16px_16px_0px_0px_rgba(0,0,0,1)] text-black relative overflow-hidden flex items-center justify-center min-h-[400px]">
              <div className="comic-burst"></div>
              <h2 className="text-7xl md:text-9xl font-black uppercase leading-none comic-text relative z-10 text-center italic">
                Siap Memulai <br />
                <span className="text-white">Cerita Baru?</span>
              </h2>
            </div>
          </ScrollReveal>
          <footer className="mt-24 font-black uppercase text-xl text-white">
            Griya Chandra Residence © 2026
          </footer>
        </div>
      </div>
    </div>
  );
}
