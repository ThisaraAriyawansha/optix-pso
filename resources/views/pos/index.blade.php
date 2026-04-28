<x-layouts.app title="New Sale">
    <div class="flex h-[calc(100vh-6rem)]" x-data="pos()">
        {{-- Product Panel (Left) --}}
        <div class="flex-1 flex flex-col overflow-hidden border-r border-[#E2E8F0] bg-white">
            {{-- Search + Category tabs --}}
            <div class="p-4 border-b border-[#E2E8F0] space-y-3">
                <input type="text" x-model="search" placeholder="Search products or scan barcode..." class="form-input w-full" @input.debounce.300="filterProducts()">
                <div class="flex gap-2 overflow-x-auto pb-1">
                    <button @click="selectedCategory = null; filterProducts()" :class="!selectedCategory ? 'bg-[#004080] text-white' : 'bg-[#F5F7FA] text-[#64748B]'" class="px-3 py-1.5 rounded-pill text-xs font-medium flex-shrink-0 transition-colors">All</button>
                    @foreach($categories as $cat)
                    <button @click="selectedCategory = '{{ $cat->id }}'; filterProducts()" :class="selectedCategory === '{{ $cat->id }}' ? 'bg-[#004080] text-white' : 'bg-[#F5F7FA] text-[#64748B]'" class="px-3 py-1.5 rounded-pill text-xs font-medium flex-shrink-0 transition-colors">{{ $cat->name }}</button>
                    @endforeach
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product)"
                             class="card p-3 cursor-pointer hover:border-[#004080] hover:shadow-md transition-all select-none"
                             :class="{'opacity-50 cursor-not-allowed': product.stock <= 0}">
                            <div class="aspect-square bg-[#F5F7FA] rounded-md mb-2 flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#CBD5E1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-[#1A202C] truncate" x-text="product.name"></p>
                            <p class="text-xs text-[#004080] font-semibold mt-0.5" x-text="'Rs. ' + Number(product.selling_price).toLocaleString()"></p>
                            <p class="text-[10px] mt-0.5" :class="product.stock <= 0 ? 'text-[#DC2626]' : 'text-[#64748B]'" x-text="product.stock + ' in stock'"></p>
                        </div>
                    </template>
                </div>
                <p x-show="filteredProducts.length === 0" class="text-center text-[#64748B] py-12">No products found</p>
            </div>
        </div>

        {{-- Cart Panel (Right) --}}
        <div class="w-96 flex flex-col bg-white">
            {{-- Customer --}}
            <div class="p-4 border-b border-[#E2E8F0]">
                <label class="form-label">Customer</label>
                <select x-model="customerId" class="form-select">
                    <option value="">Walk-in Customer</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Cart Items --}}
            <div class="flex-1 overflow-y-auto">
                <template x-if="cart.length === 0">
                    <div class="flex items-center justify-center h-32 text-[#64748B] text-sm">
                        <p>Cart is empty. Click a product to add.</p>
                    </div>
                </template>
                <template x-for="(item, idx) in cart" :key="idx">
                    <div class="flex items-center gap-3 px-4 py-3 border-b border-[#F1F5F9]">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-[#1A202C] truncate" x-text="item.name"></p>
                            <p class="text-xs text-[#64748B]" x-text="'Rs. ' + Number(item.price).toLocaleString() + ' × ' + item.qty"></p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button @click="decreaseQty(idx)" class="w-6 h-6 rounded border border-[#E2E8F0] text-[#64748B] hover:border-[#004080] text-xs flex items-center justify-center">−</button>
                            <span class="w-7 text-center text-sm font-medium" x-text="item.qty"></span>
                            <button @click="increaseQty(idx)" class="w-6 h-6 rounded border border-[#E2E8F0] text-[#64748B] hover:border-[#004080] text-xs flex items-center justify-center">+</button>
                        </div>
                        <p class="text-sm font-semibold text-[#1A202C] w-20 text-right" x-text="'Rs. ' + (item.price * item.qty).toLocaleString()"></p>
                        <button @click="removeItem(idx)" class="text-[#DC2626] hover:text-[#B91C1C]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            {{-- Totals & Payment --}}
            <div class="border-t border-[#E2E8F0] p-4 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-[#64748B]">Subtotal</span>
                    <span class="font-medium" x-text="'Rs. ' + subtotal().toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-sm text-[#64748B]">Discount</span>
                    <select x-model="discountType" class="form-select text-xs py-1 px-2 h-7 flex-1">
                        <option value="fixed">Rs. Fixed</option>
                        <option value="percent">% Percent</option>
                    </select>
                    <input type="number" x-model="discountValue" min="0" class="form-input text-xs py-1 px-2 h-7 w-24" placeholder="0">
                </div>

                <div class="flex justify-between text-base font-semibold text-[#1A202C] border-t border-[#E2E8F0] pt-3">
                    <span>Total</span>
                    <span class="text-[#004080] text-lg" x-text="'Rs. ' + total().toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                </div>

                {{-- Payment Methods --}}
                <div class="space-y-2">
                    <p class="text-sm font-medium text-[#1A202C] font-heading">Payment</p>
                    <div class="grid grid-cols-3 gap-1.5">
                        @foreach(['cash' => 'Cash', 'card' => 'Card', 'mobile_pay' => 'Mobile', 'bank_transfer' => 'Bank', 'loyalty_points' => 'Points', 'credit' => 'Credit'] as $method => $label)
                        <button @click="selectPaymentMethod('{{ $method }}')"
                                :class="paymentMethod === '{{ $method }}' ? 'bg-[#004080] text-white border-[#004080]' : 'bg-white text-[#64748B] border-[#E2E8F0]'"
                                class="py-1.5 px-2 rounded border text-xs font-medium transition-colors">{{ $label }}</button>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-[#64748B]">Amount paid:</label>
                        <input type="number" x-model="amountPaid" min="0" step="0.01" class="form-input text-sm py-1.5 flex-1">
                    </div>
                    <p class="text-sm" x-show="parseFloat(amountPaid) >= total() && total() > 0">
                        Change: <span class="font-semibold text-[#16A34A]" x-text="'Rs. ' + (parseFloat(amountPaid) - total()).toLocaleString('en-US', {minimumFractionDigits:2})"></span>
                    </p>
                </div>

                <button @click="completeSale()" :disabled="cart.length === 0 || !paymentMethod"
                        class="btn btn-primary w-full justify-center py-3 text-base disabled:opacity-50 disabled:cursor-not-allowed">
                    Issue Invoice
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    const allProducts = @json($products->map(fn($p) => [
        'id' => $p->id,
        'name' => $p->name,
        'selling_price' => $p->selling_price,
        'category_id' => $p->category_id,
        'barcode' => $p->barcode,
        'stock' => $p->stock->where('branch_id', session('active_branch_id'))->sum('qty'),
    ]));

    function pos() {
        return {
            cart: [],
            search: '',
            selectedCategory: null,
            filteredProducts: allProducts,
            customerId: '',
            discountType: 'fixed',
            discountValue: 0,
            paymentMethod: 'cash',
            amountPaid: 0,

            filterProducts() {
                this.filteredProducts = allProducts.filter(p => {
                    const matchSearch = !this.search || p.name.toLowerCase().includes(this.search.toLowerCase()) || (p.barcode && p.barcode.includes(this.search));
                    const matchCat = !this.selectedCategory || p.category_id === this.selectedCategory;
                    return matchSearch && matchCat;
                });
            },

            addToCart(product) {
                if (product.stock <= 0) return;
                const existing = this.cart.find(i => i.id === product.id);
                if (existing) {
                    existing.qty++;
                } else {
                    this.cart.push({ id: product.id, name: product.name, price: parseFloat(product.selling_price), qty: 1 });
                }
                this.amountPaid = this.total().toFixed(2);
            },

            removeItem(idx) { this.cart.splice(idx, 1); this.amountPaid = this.total().toFixed(2); },
            increaseQty(idx) { this.cart[idx].qty++; this.amountPaid = this.total().toFixed(2); },
            decreaseQty(idx) { if (this.cart[idx].qty > 1) { this.cart[idx].qty--; } else { this.removeItem(idx); } this.amountPaid = this.total().toFixed(2); },

            subtotal() { return this.cart.reduce((s, i) => s + i.price * i.qty, 0); },
            total() {
                const sub = this.subtotal();
                const disc = this.discountType === 'percent' ? sub * (parseFloat(this.discountValue) / 100) : parseFloat(this.discountValue || 0);
                return Math.max(0, sub - disc);
            },

            selectPaymentMethod(m) {
                this.paymentMethod = m;
                this.amountPaid = this.total().toFixed(2);
            },

            async completeSale() {
                if (this.cart.length === 0) return;
                const payload = {
                    customer_id: this.customerId || null,
                    discount_type: this.discountType,
                    discount_value: this.discountValue,
                    items: this.cart.map(i => ({ product_id: i.id, qty: i.qty, unit_price: i.price, discount_pct: 0 })),
                    payments: [{ method: this.paymentMethod, amount: parseFloat(this.amountPaid) }],
                };
                const res = await fetch('{{ route("pos.sale") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();
                if (data.success) {
                    this.cart = [];
                    this.discountValue = 0;
                    this.amountPaid = 0;
                    alert('Sale completed! Invoice created.');
                    if (data.invoice_id) {
                        window.open('{{ url("invoices") }}/' + data.invoice_id, '_blank');
                    }
                } else {
                    alert('Error completing sale. Please try again.');
                }
            }
        };
    }
    </script>
    @endpush
</x-layouts.app>
