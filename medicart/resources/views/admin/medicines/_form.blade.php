@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Medicine Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $medicine->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Brand <span class="text-danger">*</span></label>
        <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand', $medicine->brand ?? '') }}" required>
        @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $medicine->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Price ($) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $medicine->price ?? '') }}" required>
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Unit <span class="text-danger">*</span></label>
        <select name="unit" class="form-select" required>
            @foreach (['strip', 'bottle', 'box', 'others'] as $unit)
                <option value="{{ $unit }}" @selected(old('unit', $medicine->unit ?? '') == $unit)>{{ ucfirst($unit) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tablets per Strip</label>
        <input type="number" name="tablets_per_strip" min="1" class="form-control @error('tablets_per_strip') is-invalid @enderror" value="{{ old('tablets_per_strip', $medicine->tablets_per_strip ?? '') }}" placeholder="Only for strips">
        @error('tablets_per_strip') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Stock Quantity <span class="text">*</span></label>
        <input type="number" name="stock" min="1" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $medicine->stock ?? 0) }}" required>
        @unless (isset($medicine))
            <div class="form-text">If all medicine details match an existing product, this quantity will be added to its stock.</div>
        @endunless
        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-8 mb-3">
        <label class="form-label">Image {{ isset($medicine) ? '(leave blank to keep current image)' : '' }}</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if (isset($medicine) && $medicine->image)
            <img src="{{ $medicine->image_url }}" class="mt-2 rounded" style="width:80px;height:80px;object-fit:cover;">
        @endif
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4">{{ old('description', $medicine->description ?? '') }}</textarea>
</div>
