import React, { useEffect, useRef, useState } from 'react';
import ReactDOM from 'react-dom/client';
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
          start: "top 95%",
          end: "bottom 5%",
          toggleActions: "play none none reverse"
        }
      }
    );
  }, [direction]);

  return <div ref={elementRef} className="relative z-10">{children}</div>;
};

const DiagonalGrid = () => (
  <div
    className="absolute inset-0 pointer-events-none z-0 opacity-40"
    style={{
      backgroundImage: `
        repeating-linear-gradient(45deg, rgba(0, 0, 0, 0.15) 0, rgba(0, 0, 0, 0.15) 2px, transparent 2px, transparent 20px),
        repeating-linear-gradient(-45deg, rgba(0, 0, 0, 0.15) 0, rgba(0, 0, 0, 0.15) 2px, transparent 2px, transparent 20px)
      `,
      backgroundSize: "40px 40px",
      animation: "move-bg-vertical 2s linear infinite"
    }}
  ></div>
);


const RoomCard = ({ icon, title, description, backTitle, facilities, bgColor, backBgColor, buttonColor = "!bg-white !text-black" }) => {
  const [isFlipped, setIsFlipped] = useState(false);

  return (
    <ScrollReveal direction={title.includes("Non") ? "left" : "right"}>
      <div className="card-perspective h-[340px] md:h-[450px]">
        <div className={`card-inner ${isFlipped ? 'is-flipped' : ''}`}>
          {/* Front */}
          <div className={`card-front p-5 md:p-10 border-4 border-black ${bgColor} shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]`}>
            <div className="text-3xl md:text-5xl mb-2 md:mb-6">{icon}</div>
            <h3 className="text-3xl md:text-5xl font-black uppercase mb-1 md:mb-4 leading-tight">{title}</h3>
            <p className="font-bold text-sm md:text-lg mb-3 md:mb-8 leading-tight md:leading-normal">{description}</p>
            <div className="mt-auto">
              <RetroButton className="w-full text-xs md:text-base py-2 md:py-3" onClick={() => setIsFlipped(true)}>
                Detail Kamar
              </RetroButton>
            </div>
          </div>
          {/* Back */}
          <div className={`card-back p-5 md:p-10 border-4 border-black ${backBgColor} text-white shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]`}>
            <h3 className="text-xl md:text-3xl font-black uppercase mb-2 md:mb-6">{backTitle}</h3>
            <ul className="space-y-1 md:space-y-4 font-bold text-xs md:text-lg mb-3 md:mb-8">
              {facilities.map((fac, i) => (
                <li key={i} className="flex items-center gap-2 md:gap-3 text-white">✓ {fac}</li>
              ))}
            </ul>
            <div className="mt-auto flex gap-2 md:gap-4">
              <RetroButton className="flex-1 text-xs md:text-base py-2 md:py-3" onClick={() => setIsFlipped(false)}>
                Kembali
              </RetroButton>
              <RetroButton className={`flex-1 text-xs md:text-base py-2 md:py-3 ${buttonColor}`}>
                Pilih
              </RetroButton>
            </div>
          </div>
        </div>
      </div>
    </ScrollReveal>
  );
};

const Modal = ({ isOpen, onClose, children }) => {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-[2000] flex items-center justify-center p-4">
      {/* Backdrop */}
      {/* Backdrop */}
      <div
        className="absolute inset-0 bg-black/80 backdrop-blur-md animate-fade-in"
        onClick={onClose}
      ></div>

      {/* Modal Container */}
      <div className="bg-[#FFD100] border-4 border-black p-4 md:p-8 shadow-[20px_20px_0px_0px_#064e3b] max-w-5xl w-full relative animate-comic z-10">
        {/* Close Button */}
        <button
          onClick={onClose}
          className="absolute -top-4 -right-4 md:-top-8 md:-right-8 bg-[#FF0000] border-4 border-black w-10 h-10 md:w-14 md:h-14 flex items-center justify-center text-white font-black text-xl md:text-3xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all cursor-pointer z-20"
        >
          ✕
        </button>

        <div className="bg-white border-4 border-black overflow-hidden">
          {children}
        </div>
      </div>
    </div>
  );
};

const Landing = () => {
  const [scrollProgress, setScrollProgress] = useState(0);
  const [barColor, setBarColor] = useState('#FF6B00');
  const [showSplash, setShowSplash] = useState(true);
  const [isHeroModalOpen, setIsHeroModalOpen] = useState(false);
  const splashRef = useRef(null);
  const splashTextRef = useRef(null);

  useEffect(() => {
    if (isHeroModalOpen) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'unset';
    }
  }, [isHeroModalOpen]);

  useEffect(() => {
    // Splash Screen Animation (Responsive)
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

      if (isMobile) {
        // Mobile: One-by-one in the center
        words.forEach((word, index) => {
          tl.fromTo(word,
            { opacity: 0, scale: 0.9, y: 10 },
            { opacity: 1, scale: 1, y: 0, duration: 0.6, ease: "power2.out" }
          );
          if (index < words.length - 1) {
            tl.to(word, { opacity: 0, scale: 1.1, duration: 0.4, delay: 0.4 });
          } else {
            tl.to({}, { duration: 0.5 });
          }
        });
      } else {
        // Desktop: Sequential side-by-side reveal (no fade out)
        tl.fromTo(words,
          { opacity: 0, y: 20 },
          {
            opacity: 1,
            y: 0,
            duration: 0.8,
            stagger: 0.6,
            ease: "power3.out"
          }
        );
        tl.to({}, { duration: 1 }); // Pause before exit
      }
    });

    const handleScroll = () => {
      const totalScroll = document.documentElement.scrollHeight - window.innerHeight;
      const currentScroll = window.pageYOffset;
      setScrollProgress((currentScroll / totalScroll) * 100);
    };

    window.addEventListener('scroll', handleScroll);

    // Dynamic Color Triggers
    const sections = [
      { id: 'hero', color: '#FFD100' },
      { id: 'security', color: '#10B981' },
      { id: 'rooms', color: '#0EA5E9' },
      { id: 'cta', color: '#FF6B00' }
    ];

    sections.forEach(section => {
      ScrollTrigger.create({
        trigger: `#${section.id}`,
        start: "top 50%",
        end: "bottom 50%",
        onEnter: () => setBarColor(section.color),
        onEnterBack: () => setBarColor(section.color),
      });
    });

    return () => {
      window.removeEventListener('scroll', handleScroll);
      ScrollTrigger.getAll().forEach(t => t.kill());
    };
  }, []);

  return (
    <div className="min-h-screen bg-[#fafafa] text-black selection:bg-black selection:text-white font-['Instrument_Sans'] relative">

      {/* Splash Screen */}
      {showSplash && (
        <div
          ref={splashRef}
          className="fixed inset-0 z-[1000] bg-black flex items-center justify-center p-6"
        >
          <div
            ref={splashTextRef}
            className="text-white text-4xl md:text-6xl font-black text-center tracking-widest relative w-full flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8"
          >
            <span className="word absolute md:relative flex items-center justify-center">Hello,</span>
            <span className="word absolute md:relative flex items-center justify-center">We Are</span>
            <span className="word absolute md:relative flex items-center justify-center text-[#E65100]">Griya Chandra.</span>
          </div>
        </div>
      )}



      {/* Zipper-style Vertical Scroll Indicator (Right Side) */}
      <div className="fixed right-6 top-36 bottom-10 w-6 z-[60] bg-black/10 border-x-4 border-black hidden md:block">
        {/* Zipper Teeth (Closed part - Below) */}
        <div className="absolute inset-0 opacity-40 bg-[repeating-linear-gradient(0deg,#000,#000_4px,transparent_4px,transparent_8px)] [background-size:100%_8px]"></div>

        {/* The Zipper Pull (Handle) */}
        <div
          className="w-[140%] -left-[20%] h-16 border-4 border-black transition-all duration-300 ease-out absolute z-20 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center overflow-hidden"
          style={{
            top: `${scrollProgress}%`,
            backgroundColor: barColor,
            borderRadius: '4px 4px 12px 12px',
            transform: 'translateY(-50%)'
          }}
        >
          {/* Pull Tab Hole */}
          <div className="w-2 h-6 bg-black/20 rounded-full border-2 border-black/40"></div>
          {/* Metallic Shine */}
          <div className="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-white/20 via-transparent to-black/20 pointer-events-none"></div>
        </div>

        {/* Opened Part (Above pull) */}
        <div
          className="absolute top-0 left-0 w-full bg-gray-500/40 transition-all duration-300"
          style={{ height: `${scrollProgress}%` }}
        >
          <div className="absolute inset-0 opacity-10 bg-[radial-gradient(circle,#000_1px,transparent_1px)] [background-size:8px_8px]"></div>
        </div>
      </div>

      {/* Scroll Progress Bar (Top) */}
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

          {/* Centered About Element - Now visible on mobile */}
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

      {/* STICKY STACKED SECTIONS CONTAINER */}
      <div className="relative">

        {/* 1. HERO SECTION (Yellow) */}
        <div id="hero" className="relative md:sticky md:top-0 h-screen flex items-center bg-[#FFD100] z-10 border-b-4 border-black overflow-hidden">
          <DiagonalGrid />
          <main className="w-full max-w-[1440px] mx-auto px-6 lg:px-20 pt-20 pb-0 relative z-10">
            <section className="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-40 items-center">
              <ScrollReveal direction="left">
                <div className="inline-block px-4 py-1 bg-[#10B981] border-4 border-black font-black uppercase text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] mb-8 text-white">
                  Berdiri Sejak - 2014
                </div>
                <h1 className="text-7xl md:text-9xl font-black leading-[0.85] uppercase tracking-tighter mb-10">
                  <span className="text-[#10B981] italic text-stroke-black">Griya</span> <br /> <span className="text-[#FF6B00] italic text-stroke-black">Chandra</span><span className="text-[#10B981] text-stroke-black">.</span>
                </h1>
                <p className="text-2xl font-bold leading-tight max-w-xl mb-0 border-l-8 border-[#FF6B00] pl-6">
                  <span className="text-[#10B981]">Indekost dengan konsep</span> <span className="text-[#FF6B00]">semi apartemen</span>
                </p>
              </ScrollReveal>

              <ScrollReveal direction="right">
                <div className="flex justify-center lg:justify-end items-center p-4">
                  <div
                    onClick={() => setIsHeroModalOpen(true)}
                    className="bg-[#10B981] rounded-lg shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] border-4 border-black w-full max-w-md transform duration-500 hover:translate-x-5 hover:translate-y-5 cursor-pointer"
                  >
                    <img
                      className="rounded-lg shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] border-4 border-black transform duration-500 hover:-translate-x-10 hover:-translate-y-10 w-full object-cover"
                      src="https://images.unsplash.com/photo-1604489141828-ca67d245c447"
                      alt="Griya Chandra Hero"
                    />
                  </div>
                </div>
              </ScrollReveal>
            </section>
          </main>
        </div>

        {/* 2. SECURITY SECTION (Green) */}
        <div id="security" className="relative md:sticky md:top-0 min-h-screen flex items-center bg-[#10B981] z-20 border-t-4 border-black py-12 md:py-0">
          <div className="absolute inset-0 opacity-10 bg-[repeating-linear-gradient(45deg,#000,#000_10px,transparent_10px,transparent_20px)] animate-bg-diagonal"></div>
          <div className="max-w-7xl mx-auto px-6 lg:px-20 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10 w-full">
            <ScrollReveal direction="right">
              <div className="flex justify-center lg:justify-start">
                <div className="gallery">
                  <img src="https://picsum.photos/id/174/400/400" alt="a hot air balloon" />
                  <img src="https://picsum.photos/id/188/400/400" alt="a sky photo of an old city" />
                  <img src="https://picsum.photos/id/211/400/400" alt="a small boat" />
                  <img src="https://picsum.photos/id/28/400/400" alt="a forest" />
                </div>
              </div>
            </ScrollReveal>
            <ScrollReveal direction="left">
              <h2 className="text-6xl md:text-7xl font-black uppercase mb-8 leading-none text-black">Keamanan <br /> Berbasis <br /> <span className="text-white">Teknologi.</span></h2>
              <p className="text-xl font-bold leading-relaxed text-black">
                Kami menggabungkan kenyamanan desain retro dengan sistem keamanan tercanggih. Smart lock, CCTV di setiap sudut, dan sistem monitoring real-time.
              </p>
            </ScrollReveal>
          </div>
        </div>

        {/* 3. ROOM SECTION (Blue) */}
        <div id="rooms" className="relative md:sticky md:top-0 min-h-screen flex items-start md:items-center bg-[#0EA5E9] z-30 border-t-4 border-black py-16 md:py-0">
          <div className="absolute inset-0 opacity-20 bg-[radial-gradient(#000_2px,transparent_2px)] [background-size:24px_24px] animate-bg-dots"></div>
          <div className="max-w-7xl mx-auto px-6 lg:px-20 relative z-10 w-full">
            <ScrollReveal>
              <div className="text-center mb-10 md:mb-20">
                <h2 className="text-5xl md:text-7xl font-black uppercase tracking-tighter text-white">Pilihan Kamar</h2>
              </div>
            </ScrollReveal>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
              <RoomCard
                icon="❄️"
                title="Tipe AC"
                description="Kenyamanan maksimal dengan pendingin udara dan fasilitas lengkap."
                backTitle="Fasilitas Utama"
                facilities={["AC Daikin 1/2 PK", "Smart TV 32 Inch", "Kamar Mandi Dalam", "Meja & Kursi Kerja"]}
                bgColor="bg-[#FFD100]"
                backBgColor="bg-black"
                buttonColor="!bg-[#FF6B00] !text-white"
              />
              <RoomCard
                icon="🌿"
                title="Tipe Non-AC"
                description="Kesegaran alami dengan sirkulasi udara yang dirancang khusus."
                backTitle="Fasilitas Utama"
                facilities={["Exhaust Fan Turbo", "Kasur Springbed", "Lemari Pakaian", "Free Wi-Fi 5G"]}
                bgColor="bg-white"
                backBgColor="bg-[#10B981]"
                buttonColor="!bg-white !text-black"
              />
            </div>
          </div>
        </div>

        {/* 4. FINAL CTA SECTION (Orange) */}
        <div id="cta" className="relative md:sticky md:top-0 min-h-screen flex items-center bg-[#FF6B00] z-40 border-t-4 border-black py-12 md:py-0">
          <div className="absolute inset-0 opacity-10 bg-[repeating-linear-gradient(0deg,transparent,transparent_20px,#000_20px,#000_23px)] animate-bg-vertical"></div>
          <div className="max-w-7xl mx-auto px-6 lg:px-20 relative z-10 text-center w-full">
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
      <Modal isOpen={isHeroModalOpen} onClose={() => setIsHeroModalOpen(false)}>
        <div className="relative">
          <img
            src="https://images.unsplash.com/photo-1604489141828-ca67d245c447"
            alt="Griya Chandra Detail"
            className="w-full h-auto"
          />
        </div>
      </Modal>
    </div>
  );
};

if (document.getElementById('landing-root')) {
  ReactDOM.createRoot(document.getElementById('landing-root')).render(<Landing />);
}
