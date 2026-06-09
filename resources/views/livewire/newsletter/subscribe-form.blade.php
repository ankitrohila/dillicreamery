<div>
    @if($subscribed)
    <div class="flex items-center gap-3 bg-white/20 rounded-2xl px-6 py-4 text-white">
        <svg class="w-6 h-6 text-gold-300 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span class="font-medium">{{ $message }}</span>
    </div>
    @else
    <form wire:submit="subscribe" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="email" wire:model="email" placeholder="Enter your email"
                   class="w-full px-5 py-3.5 rounded-full bg-white/20 text-white placeholder-white/60 border border-white/30 focus:outline-none focus:border-white focus:bg-white/30 backdrop-blur-sm">
            @error('email') <p class="text-red-300 text-xs mt-1 ml-4">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-gold whitespace-nowrap" wire:loading.attr="disabled">
            <span wire:loading.remove>Subscribe Free</span>
            <span wire:loading>Subscribing...</span>
        </button>
    </form>
    @endif
</div>
