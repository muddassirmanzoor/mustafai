<select name="country_id" class="js-example-basic-single form-control" onchange="updateFilter(this)" data-select="country">
    <option value="">Select country</option>
    @forelse($countries as $country)
        <option value="{{ $country->id }}" {{ auth()->user()->country_id == $country->id ? 'selected' : '' }}>{{ $country->name_english }}</option>
    @empty
        <option value="">No country yet!</option>
    @endforelse
</select>
