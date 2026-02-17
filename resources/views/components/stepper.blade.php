@props([
    'id' => 'stepper',
    'steps' => [], // Array of ['icon' => 'user', 'label' => 'Step 1', 'step' => 1]
    'currentStep' => 1,
])

@php
  $totalSteps = count($steps);
@endphp

@if($totalSteps > 0)
  <div {{ $attributes->merge(['class' => 'mt-2']) }} data-stepper="{{ $id }}">
    <div class="relative">
      {{-- Connecting Line --}}
      <div class="absolute left-6 right-6 top-5 h-px bg-slate-200"></div>
      
      {{-- Steps --}}
      <div class="grid gap-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 sm:text-xs" style="grid-template-columns: repeat({{ $totalSteps }}, minmax(0, 1fr))">
        @foreach($steps as $step)
          @php
            $stepNumber = $step['step'] ?? $loop->iteration;
            $isActive = $stepNumber == $currentStep;
            $isCompleted = $stepNumber < $currentStep;
            
            $indicatorClasses = $isActive 
              ? 'bg-teal-600 text-white border-teal-100 shadow-md' 
              : ($isCompleted 
                ? 'bg-teal-100 text-teal-700 border-teal-200 shadow-sm'
                : 'bg-white text-slate-400 border-slate-200 shadow-sm');
            
            $labelClasses = $isActive 
              ? 'text-teal-700' 
              : ($isCompleted ? 'text-teal-600' : 'text-slate-400');
          @endphp
          
          <div class="relative z-10 flex flex-col items-center gap-2 text-center">
            {{-- Step Indicator --}}
            <div 
              data-step-indicator="{{ $stepNumber }}" 
              class="flex h-10 w-10 items-center justify-center rounded-full border transition-all duration-200 {{ $indicatorClasses }}"
            >
              @if($isCompleted)
                <i data-lucide="check" class="h-4 w-4"></i>
              @else
                <i data-lucide="{{ $step['icon'] ?? 'circle' }}" class="h-4 w-4"></i>
              @endif
            </div>
            
            {{-- Step Label --}}
            <span data-step-label="{{ $stepNumber }}" class="transition-colors duration-200 {{ $labelClasses }}">
              {{ $step['label'] ?? 'Step ' . $stepNumber }}
            </span>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endif
