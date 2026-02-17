    <div class="hidden" data-membership-options>
      @foreach ($memberTypes as $type)
        @php
          $isDailyOption = $type->is_daily || \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($type->name), 'harian');
        @endphp
        <span data-membership-option data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-price="{{ $type->pricing }}" data-is-student="{{ $type->is_student ? '1' : '0' }}" data-kind="{{ $isDailyOption ? 'ticket' : 'member' }}"></span>
      @endforeach
    </div>
  </div>
