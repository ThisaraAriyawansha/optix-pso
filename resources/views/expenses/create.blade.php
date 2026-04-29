<x-layouts.app title="Record Expense">
    <x-page-header title="Record Expense" :breadcrumbs="[['label' => 'Expenses', 'url' => route('expenses.index')], ['label' => 'New', 'url' => '#']]"/>
    <div class="p-6">
        <form method="POST" action="{{ route('expenses.store') }}" class="max-w-xl">
            @csrf
            <x-card class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="form-label">Description *</label>
                        <input type="text" name="description" value="{{ old('description') }}" class="form-input" required placeholder="What was this expense for?">
                        @error('description')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Amount (Rs.) *</label>
                        <input type="number" name="amount" value="{{ old('amount') }}" min="0.01" step="0.01" class="form-input" required>
                        @error('amount')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Date *</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', today()->toDateString()) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            @foreach(['rent','utilities','salaries','supplies','maintenance','advertising','transport','other'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            @foreach(['cash','card','bank_transfer','online'] as $m)
                                <option value="{{ $m }}" {{ old('payment_method') == $m ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$m)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="2" class="form-textarea">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </x-card>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Record Expense</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.app>
