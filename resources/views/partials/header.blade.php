<div class="top-bar group -mt-2 [&.scrolled]:sticky [&.scrolled]:inset-x-0 [&.scrolled]:top-0 [&.scrolled]:z-[999] [&.scrolled]:mt-0">
    <div class="flex h-16 items-center gap-5 border-b transition-all group-[.scrolled]:px-5 group-[.scrolled]:rounded-2xl group-[.scrolled]:bg-background group-[.scrolled]:border group-[.scrolled]:shadow-lg group-[.scrolled]:shadow-foreground/5">

        <!-- Mobile Menu Toggle -->
        <div class="open-mobile-menu bg-background mr-auto flex size-9 cursor-pointer items-center justify-center rounded-xl border xl:hidden">
            <i data-lucide="chart-no-axes-column" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 rotate-90"></i>
        </div>

        <!-- Breadcrumb Navigation -->
        <ul class="truncate gap-x-6 [--color-link:var(--color-primary)] [--color-base:var(--color-foreground)] mr-auto hidden xl:flex">
            @if(isset($breadcrumb) && is_array($breadcrumb))
                @foreach($breadcrumb as $index => $item)
                    <li class="[&:not(:last-child)>a]:text-(--color-link) text-(--color-base) before:bg-(image:--background-image-chevron) relative before:absolute before:inset-y-0 before:my-auto before:-ml-4 before:size-2 before:-rotate-90 before:bg-center before:bg-no-repeat before:opacity-70 first:before:hidden">
                        @if($index === count($breadcrumb) - 1)
                            <!-- Current Page -->
                            <span>{{ $item['title'] }}</span>
                        @else
                            <!-- Link -->
                            <a href="{{ $item['url'] ?? '#' }}">{{ $item['title'] }}</a>
                        @endif
                    </li>
                @endforeach
            @else
                <!-- Default Breadcrumb -->
                <li class="[&:not(:last-child)>a]:text-(--color-link) text-(--color-base) before:bg-(image:--background-image-chevron) relative before:absolute before:inset-y-0 before:my-auto before:-ml-4 before:size-2 before:-rotate-90 before:bg-center before:bg-no-repeat before:opacity-70 first:before:hidden">
                    <a href="{{ route('dashboard.index') }}">Dashboard</a>
                </li>
                <li class="[&:not(:last-child)>a]:text-(--color-link) text-(--color-base) before:bg-(image:--background-image-chevron) relative before:absolute before:inset-y-0 before:my-auto before:-ml-4 before:size-2 before:-rotate-90 before:bg-center before:bg-no-repeat before:opacity-70 first:before:hidden">
                    <span>@yield('page-title', 'Overview')</span>
                </li>
            @endif
        </ul>

        <!-- Quick Search -->
        <div class="quick-search-toggle bg-background hover:ring-foreground/5 flex h-9 cursor-pointer items-center rounded-full border px-4 ring-1 ring-transparent ring-offset-2 ring-offset-transparent">
            <div class="flex items-center gap-3 opacity-70">
                <i data-lucide="search" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                ⌘K
            </div>
        </div>

        <!-- Notifications -->
        <div class="group/notifications relative flex h-9 items-center">
            <i data-lucide="bell" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>

            <!-- Notifications Dropdown -->
            <div class="hidden group-hover/notifications:block">
                <div class="box p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:shadow-foreground/5 absolute right-0 top-0 z-50 -mr-0.5 -mt-0.5 flex w-96 flex-col gap-2.5 px-6 py-5 before:rounded-2xl before:shadow-xl before:backdrop-blur after:rounded-2xl">
                    <div class="flex place-content-between items-center">
                        <div class="font-medium">Notifications</div>
                        <a class="text-primary text-xs" href="#">View More</a>
                    </div>

                    <div class="mt-1 flex flex-col gap-2.5">
                        <!-- Sample Notification Items -->
                        <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-2 flex items-center gap-3.5 rounded-2xl border border-transparent p-2" href="#">
                            <div class="border-background/60 dark:border-foreground/[.05] relative h-14 w-14 flex-none overflow-hidden rounded-full border-4">
                                <img class="absolute top-0 h-full w-full object-cover" src="{{ asset('backend/assets/images/fakers/profile-8.jpg') }}" alt="Notification User">
                            </div>
                            <div class="w-full">
                                <div class="text-base font-medium">New parking request</div>
                                <div class="mt-0.5 text-xs opacity-70">Vehicle ABC-123 requested parking space</div>
                                <div class="mt-1.5 text-xs opacity-60">2 minutes ago</div>
                            </div>
                        </a>

                        <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-2 flex items-center gap-3.5 rounded-2xl border border-transparent p-2" href="#">
                            <div class="border-background/60 dark:border-foreground/[.05] relative h-14 w-14 flex-none overflow-hidden rounded-full border-4">
                                <div class="bg-primary/20 flex h-full w-full items-center justify-center">
                                    <i data-lucide="car" class="text-primary size-6"></i>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="text-base font-medium">Space became available</div>
                                <div class="mt-0.5 text-xs opacity-70">Parking space A-12 is now free</div>
                                <div class="mt-1.5 text-xs opacity-60">5 minutes ago</div>
                            </div>
                        </a>

                        <a class="hover:border-foreground/10 hover:bg-foreground/5 -mx-2 flex items-center gap-3.5 rounded-2xl border border-transparent p-2" href="#">
                            <div class="border-background/60 dark:border-foreground/[.05] relative h-14 w-14 flex-none overflow-hidden rounded-full border-4">
                                <div class="bg-amber-500/20 flex h-full w-full items-center justify-center">
                                    <i data-lucide="clock" class="text-amber-500 size-6"></i>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="text-base font-medium">Payment reminder</div>
                                <div class="mt-0.5 text-xs opacity-70">Vehicle XYZ-789 payment is overdue</div>
                                <div class="mt-1.5 text-xs opacity-60">1 hour ago</div>
                            </div>
                        </a>
                    </div>

                    <!-- View All Link -->
                    <div class="border-foreground/5 border-t pt-2.5">
                        <a class="text-primary flex items-center justify-center gap-2 text-sm font-medium" href="#">
                            View All Notifications
                            <i data-lucide="external-link" class="size-4"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
