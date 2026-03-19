<footer class="bg-gray-800 text-gray-300 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <!-- Copyright Information -->
            <div class="text-sm">
                © {{ date('Y') }} Parking Management System. All rights reserved.
            </div>

            <!-- Footer Links -->
            <div class="flex items-center gap-6 text-sm">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-white transition-colors">Support</a>
                <a href="#" class="hover:text-white transition-colors">Documentation</a>
            </div>
        </div>

        <!-- Additional Footer Info -->
        <div class="mt-4 pt-4 border-t border-gray-700 text-center">
            <div class="text-xs">
                Version 1.0.0 | Last updated: {{ date('F j, Y') }}
            </div>
        </div>
    </div>
</footer>
