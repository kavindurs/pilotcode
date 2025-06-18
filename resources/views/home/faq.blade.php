<section class="py-10 bg-gray-50 sm:py-16 lg:py-24">
    <div class="px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl xl:text-5xl font-pj">Frequently Asked Questions</h2>
            <p class="max-w-xl mx-auto mt-4 text-base leading-relaxed text-gray-600">Everything you need to know about reviews, businesses, and Scoreness platform</p>
        </div>

        <div class="max-w-3xl mx-auto mt-8 space-y-4 md:mt-16" x-data="{selected:1}">
            <div class="transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                <button type="button" @click="selected !== 1 ? selected = 1 : selected = null" class="flex items-center justify-between w-full px-4 py-5 sm:p-6">
                    <span class="flex text-lg font-semibold text-gray-900">How do I submit a review on Scoreness?</span>
                    <svg class="w-6 h-6 text-blue-600" :class="{'rotate-180': selected == 1}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div class="px-4 pb-5 sm:px-6 sm:pb-6" x-show="selected == 1" x-transition>
                    <p class="text-gray-700">To submit a review on Scoreness, first search for the business you'd like to review. Once on the business page, click the "Write a Review" button. You'll be asked to rate the business (1-5 stars) and share your experience. If you haven't created an account yet, you'll be prompted to register or log in before your review is submitted.</p>
                </div>
            </div>

            <div class="transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                <button type="button" @click="selected !== 2 ? selected = 2 : selected = null" class="flex items-center justify-between w-full px-4 py-5 sm:p-6">
                    <span class="flex text-lg font-semibold text-gray-900">How does Scoreness verify reviews?</span>
                    <svg class="w-6 h-6 text-blue-600" :class="{'rotate-180': selected == 2}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div class="px-4 pb-5 sm:px-6 sm:pb-6" x-show="selected == 2" x-transition>
                    <p class="text-gray-700">At Scoreness, we take review authenticity seriously. All reviews go through our verification process that checks for spam, fake accounts, and policy violations. We use a combination of automated systems and human moderators to ensure reviews are genuine. Users must have verified accounts, and businesses cannot pay to remove or alter negative reviews.</p>
                </div>
            </div>

            <div class="transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                <button type="button" @click="selected !== 3 ? selected = 3 : selected = null" class="flex items-center justify-between w-full px-4 py-5 sm:p-6">
                    <span class="flex text-lg font-semibold text-gray-900">How can I claim my business on Scoreness?</span>
                    <svg class="w-6 h-6 text-blue-600" :class="{'rotate-180': selected == 3}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div class="px-4 pb-5 sm:px-6 sm:pb-6" x-show="selected == 3" x-transition>
                    <p class="text-gray-700">To claim your business on Scoreness, search for your business profile and click the "Claim This Business" button. You'll need to create a business account and verify that you're the owner or authorized representative through our verification process, which may include confirmation of business documents, phone verification, or other methods to ensure legitimate ownership.</p>
                </div>
            </div>

            <div class="transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                <button type="button" @click="selected !== 4 ? selected = 4 : selected = null" class="flex items-center justify-between w-full px-4 py-5 sm:p-6">
                    <span class="flex text-lg font-semibold text-gray-900">Can businesses respond to reviews?</span>
                    <svg class="w-6 h-6 text-blue-600" :class="{'rotate-180': selected == 4}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div class="px-4 pb-5 sm:px-6 sm:pb-6" x-show="selected == 4" x-transition>
                    <p class="text-gray-700">Yes, business owners with verified accounts can respond to any review posted about their business. Responses appear publicly beneath the original review. This is an excellent opportunity to thank customers for positive feedback or address concerns raised in negative reviews. Constructive, professional responses can significantly impact how potential customers view your business.</p>
                </div>
            </div>

            <div class="transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                <button type="button" @click="selected !== 5 ? selected = 5 : selected = null" class="flex items-center justify-between w-full px-4 py-5 sm:p-6">
                    <span class="flex text-lg font-semibold text-gray-900">What if I disagree with a review about my business?</span>
                    <svg class="w-6 h-6 text-blue-600" :class="{'rotate-180': selected == 5}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div class="px-4 pb-5 sm:px-6 sm:pb-6" x-show="selected == 5" x-transition>
                    <p class="text-gray-700">If you disagree with a review about your business, you have several options. First, you can publicly respond to the review to provide your perspective. If you believe the review violates our guidelines (contains false information, harassment, etc.), you can report it for our moderation team to review. We don't remove reviews simply because they're negative, but we do take action against reviews that violate our policies.</p>
                </div>
            </div>

            <div class="transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                <button type="button" @click="selected !== 6 ? selected = 6 : selected = null" class="flex items-center justify-between w-full px-4 py-5 sm:p-6">
                    <span class="flex text-lg font-semibold text-gray-900">Is Scoreness free to use?</span>
                    <svg class="w-6 h-6 text-blue-600" :class="{'rotate-180': selected == 6}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div class="px-4 pb-5 sm:px-6 sm:pb-6" x-show="selected == 6" x-transition>
                    <p class="text-gray-700">Yes, Scoreness is completely free for consumers to use. You can read reviews, create an account, and submit your own reviews at no cost. For businesses, we offer both free and premium options. The basic business account is free and allows you to claim your business, respond to reviews, and update basic information. Premium business plans offer additional features like analytics, enhanced profiles, and promotional tools.</p>
                </div>
            </div>
        </div>

        <p class="text-center text-gray-600 mt-9">Didn't find the answer you are looking for? <a href="#" class="font-medium text-blue-600 transition-all duration-200 hover:text-blue-700 focus:text-blue-700 hover:underline">Contact our support team</a></p>
    </div>
</section>

<!-- Alpine.js for interactive accordion -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
