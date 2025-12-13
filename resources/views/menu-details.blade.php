<div id="foodDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-[9999] p-4">
    <div class="food-details-section bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-auto relative max-h-[90vh] flex flex-col overflow-y-auto">
        
        <button class="back-to-list absolute top-4 left-4 text-gray-600 hover:text-gray-800 z-10 flex items-center gap-2 bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-200" id="closeDetailsBtn">
            <i class="material-icons text-xl">arrow_back</i>
            <span class="text-sm font-medium">Back to Menu</span>
        </button>

        <div class="food-details-container pt-16 flex-1 overflow-y-auto">
            <div class="food-details-header flex flex-col lg:flex-row gap-8 p-8">
                <div class="food-details-image w-full lg:w-2/5">
                    <img id="detailImage" src="" class="w-full h-80 object-cover rounded-2xl shadow-lg" alt="Food image">
                </div>

                <div class="food-details-info w-full lg:w-3/5">
                    <h2 id="detailName" class="text-3xl font-bold mb-3 text-gray-800"></h2>
                    <div id="detailPrice" class="text-3xl font-extrabold text-orange-500 mb-6"></div>
                    
                    <p id="detailDesc" class="text-gray-600 text-base mb-8 leading-relaxed"></p>

                    <div class="food-details-actions flex items-center gap-4">
                        <div class="quantity-selector flex items-center bg-gray-100 rounded-xl border border-gray-200">
                            <button class="quantity-btn minus w-12 h-12 text-xl font-bold text-gray-600 hover:bg-gray-200 rounded-l-xl transition-colors" data-action="minus">-</button>
                            <span class="quantity w-12 text-center font-bold text-lg text-gray-800">1</span>
                            <button class="quantity-btn plus w-12 h-12 text-xl font-bold text-gray-600 hover:bg-gray-200 rounded-r-xl transition-colors" data-action="plus">+</button>
                        </div>
                        
                        <button id="detailAddBtn" class="add-to-cart-large bg-orange-500 text-white py-4 px-8 rounded-xl font-bold hover:bg-orange-600 flex items-center justify-center gap-3 shadow-lg transition-all duration-300 flex-1">
                            <i class="material-icons text-xl">add_shopping_cart</i>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            <div class="food-details-meta grid grid-cols-2 lg:grid-cols-2 gap-4 px-8 pb-8">
                <div class="meta-card bg-orange-50 border-l-4 border-orange-500 py-5 px-6 rounded-xl text-center">
                    <h4 class="text-xs font-semibold uppercase text-orange-800 mb-2">Preparation Time</h4>
                    <p id="detailPrep" class="text-lg font-bold text-orange-600"></p>
                </div>

                <div class="meta-card bg-orange-50 border-l-4 border-orange-500 py-5 px-6 rounded-xl text-center">
                    <h4 class="text-xs font-semibold uppercase text-orange-800 mb-2">Category</h4>
                    <p id="detailCat" class="text-lg font-bold text-orange-600"></p>
                </div>
            </div>
        </div>
    </div>
</div>