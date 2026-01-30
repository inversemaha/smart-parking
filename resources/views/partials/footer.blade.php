<!-- Footer Content -->
<div class="mt-16 border-t pt-8">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <!-- Copyright Information -->
        <div class="text-sm opacity-70">
            © {{ date('Y') }} Parking Management System. All rights reserved.
        </div>

        <!-- Footer Links -->
        <div class="flex items-center gap-6 text-sm">
            <a href="#" class="opacity-70 hover:opacity-100 transition-opacity">Privacy Policy</a>
            <a href="#" class="opacity-70 hover:opacity-100 transition-opacity">Terms of Service</a>
            <a href="#" class="opacity-70 hover:opacity-100 transition-opacity">Support</a>
            <a href="#" class="opacity-70 hover:opacity-100 transition-opacity">Documentation</a>
        </div>
    </div>

    <!-- Additional Footer Info -->
    <div class="mt-4 pt-4 border-t text-center">
        <div class="text-xs opacity-60">
            Version 1.0.0 | Last updated: {{ date('F j, Y') }}
        </div>
    </div>
</div>

<!-- Onboarding Dialog Modal (Optional) -->
@if(session('show_onboarding', false))
<div data-tw-backdrop="" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 show visible opacity-100" id="onboarding-dialog">
    <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl sm:max-w-xl">
        <a class="bg-background/80 hover:bg-background absolute right-0 top-0 -mr-3 -mt-3 flex size-9 items-center justify-center rounded-full border shadow outline-none backdrop-blur" data-tw-dismiss="modal" href="#">
            <i data-lucide="x" class="stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-5 opacity-70"></i>
        </a>

        <div class="-mx-3 pb-5">
            <div class="relative mx-3 flex flex-col items-center gap-1 px-3.5">
                <div class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                    <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125" src="{{ asset('backend/assets/images/phone-illustration.svg') }}" alt="Welcome Illustration">
                </div>

                <div class="px-8">
                    <div class="text-center text-xl font-medium">Welcome to Parking Management System!</div>
                    <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                        Modern admin dashboard for comprehensive parking management.
                        Monitor parking spaces, track vehicles, and generate reports with ease.
                    </div>
                </div>

                <div class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                    <a class="text-danger flex items-center gap-3 font-medium" data-tw-dismiss="modal" href="#">
                        Skip Intro
                    </a>
                    <a class="text-primary flex items-center gap-3 font-medium" href="#">
                        Get Started
                        <i data-lucide="move-right" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
