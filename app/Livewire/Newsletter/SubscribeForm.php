<?php
namespace App\Livewire\Newsletter;

use App\Models\NewsletterSubscriber;
use Livewire\Component;

class SubscribeForm extends Component
{
    public string $email = '';
    public string $name = '';
    public bool $subscribed = false;
    public string $message = '';

    protected $rules = [
        'email' => 'required|email|max:255',
        'name' => 'nullable|string|max:255',
    ];

    public function subscribe(): void
    {
        $this->validate();

        $existing = NewsletterSubscriber::where('email', $this->email)->first();

        if ($existing) {
            if (!$existing->is_active) {
                $existing->update(['is_active' => true, 'unsubscribed_at' => null]);
                $this->subscribed = true;
                $this->message = 'Welcome back! You\'re subscribed again.';
            } else {
                $this->addError('email', 'You\'re already subscribed!');
            }
            return;
        }

        NewsletterSubscriber::create([
            'email' => $this->email,
            'name' => $this->name,
            'is_active' => true,
            'confirmed_at' => now(),
            'source' => 'website_footer',
        ]);

        $this->subscribed = true;
        $this->message = 'Thank you! You\'re now subscribed to our newsletter. 🎉';
        $this->email = '';
        $this->name = '';
    }

    public function render()
    {
        return view('livewire.newsletter.subscribe-form');
    }
}
