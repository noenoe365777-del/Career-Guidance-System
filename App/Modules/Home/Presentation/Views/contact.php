<?php
$pageTitle = 'Contact Us - Career Guidance System';
?>

<section class="relative overflow-hidden bg-gradient-to-b from-white via-indigo-50/20 to-slate-50 py-20 lg:py-28">
    <div class="absolute top-0 right-0 -z-10 h-72 w-72 rounded-full bg-indigo-200/30 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -z-10 h-96 w-96 rounded-full bg-blue-200/20 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <span class="inline-flex items-center gap-1.5 py-1.5 px-3.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 mb-5">
            Get in Touch
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 mb-6 max-w-3xl mx-auto leading-tight">
            We’re here to help you
            <span class="bg-gradient-to-r from-brand-start via-brand-mid to-brand-end bg-clip-text text-transparent">make the right next move</span>
        </h1>
        <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto font-medium leading-relaxed">
            Whether you need help with assessments, account support, or career guidance, our team is ready to assist you quickly and clearly.
        </p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-12 lg:py-16">
    <div class="grid lg:grid-cols-5 gap-8 lg:gap-10 items-start">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-3">Contact Information</h3>
                <p class="text-slate-600 font-medium leading-relaxed">
                    Reach out through any of the options below and we’ll get back to you as soon as possible.
                </p>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-4 p-5 bg-white border border-slate-200/60 rounded-2xl shadow-sm hover:border-indigo-200 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Email</p>
                        <a href="mailto:support@careerguidance.com" class="text-sm font-semibold text-slate-800 hover:text-indigo-600 transition-colors">support@careerguidance.com</a>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-5 bg-white border border-slate-200/60 rounded-2xl shadow-sm hover:border-indigo-200 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-700 flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Phone</p>
                        <p class="text-sm font-semibold text-slate-800">+1 (555) 234-5678</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-5 bg-white border border-slate-200/60 rounded-2xl shadow-sm hover:border-indigo-200 transition-all group">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-700 flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Office</p>
                        <p class="text-sm font-semibold text-slate-800 leading-relaxed">100 Innovation Way, Suite 400<br>Tech District, NY 10001</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 bg-white border border-slate-100 shadow-xl shadow-slate-200/40 rounded-[2rem] p-8 sm:p-10">
            <h3 class="text-xl font-bold text-slate-900 mb-2">Send us a message</h3>
            <p class="text-slate-500 font-medium text-sm mb-8">Tell us what you need and we’ll guide you through it.</p>

            <form action="<?= BASE_URL ?>/index.php?page=contact_submit" method="POST" class="space-y-5">
                <div class="grid sm:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label for="name" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Your Name</label>
                        <input type="text" id="name" name="name" required placeholder="John Doe"
                            class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50/70 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Email Address</label>
                        <input type="email" id="email" name="email" required placeholder="john@example.com"
                            class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50/70 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="subject" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Inquiry Subject</label>
                    <input type="text" id="subject" name="subject" required placeholder="How can we assist you?"
                        class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50/70 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                </div>

                <div class="space-y-2">
                    <label for="message" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Message</label>
                    <textarea id="message" name="message" rows="5" required placeholder="Please write your questions here..."
                        class="w-full p-4 rounded-xl border border-slate-200 bg-slate-50/70 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all resize-none"></textarea>
                </div>

                <button type="submit"
                    class="w-full h-12 bg-gradient-to-r from-brand-start via-brand-mid to-brand-end text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg active:scale-[0.99] transition-all duration-200">
                    Send Message <i class="fas fa-paper-plane ml-2 text-[10px] opacity-80"></i>
                </button>
            </form>
        </div>
    </div>
</section>