<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
            <p class="text-gray-500 mt-1">Configure your application settings</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')

                <!-- General Settings -->
                <x-card class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">General Settings</h2>
                    
                    <div class="space-y-4">
                        @if(isset($settings['general']))
                            @foreach($settings['general'] as $setting)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                    </label>
                                    @if($setting->type === 'boolean')
                                        <select name="settings[{{ $setting->key }}]" 
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                            <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>No</option>
                                        </select>
                                    @elseif($setting->type === 'json')
                                        <textarea name="settings[{{ $setting->key }}]" rows="3"
                                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200 font-mono text-sm">{{ $setting->value }}</textarea>
                                    @else
                                        <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200">
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">No general settings configured. </p>
                        @endif
                    </div>
                </x-card>

                <!-- Business Settings -->
                <x-card class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Business Settings</h2>
                    
                    <div class="space-y-4">
                        @if(isset($settings['business']))
                            @foreach($settings['business'] as $setting)
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-sm font-medium text-gray-700">
                                        {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                    </label>
                                    <div class="col-span-2">
                                        @if($setting->key === 'currency')
                                            <select name="settings[{{ $setting->key }}]" 
                                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                                <option value="USD" {{ $setting->value == 'USD' ?  'selected' : '' }}>USD - US Dollar</option>
                                                <option value="EUR" {{ $setting->value == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                                <option value="GBP" {{ $setting->value == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                                <option value="IDR" {{ $setting->value == 'IDR' ? 'selected' : '' }}>IDR - Indonesian Rupiah</option>
                                            </select>
                                        @elseif($setting->type === 'integer')
                                            <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200">
                                        @else
                                            <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">No business settings configured.</p>
                        @endif
                    </div>
                </x-card>

                <!-- Operations Settings -->
                <x-card class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Operations Settings</h2>
                    
                    <div class="space-y-4">
                        @if(isset($settings['operations']))
                            @foreach($settings['operations'] as $setting)
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-sm font-medium text-gray-700">
                                        {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                    </label>
                                    <div class="col-span-2">
                                        @if(str_contains($setting->key, 'time'))
                                            <input type="time" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200">
                                        @elseif($setting->type === 'json')
                                            <div class="flex flex-wrap gap-2">
                                                @php $days = json_decode($setting->value, true) ??  []; @endphp
                                                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" name="operating_days[]" value="{{ $day }}"
                                                               {{ in_array($day, $days) ? 'checked' : '' }}
                                                               class="rounded border-gray-300 text-eco-600 focus:ring-eco-500">
                                                        <span class="ml-2 text-sm text-gray-700">{{ ucfirst($day) }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @else
                                            <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus: border-eco-500 focus: ring-2 focus:ring-eco-200">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">No operations settings configured.</p>
                        @endif
                    </div>
                </x-card>

                <!-- Notification Settings -->
                <x-card class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Notification Settings</h2>
                    
                    <div class="space-y-4">
                        @if(isset($settings['notifications']))
                            @foreach($settings['notifications'] as $setting)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</p>
                                        <p class="text-sm text-gray-500">
                                            @switch($setting->key)
                                                @case('send_booking_confirmation')
                                                    Send confirmation email when a booking is made
                                                    @break
                                                @case('send_reminder_hours_before')
                                                    Hours before pickup to send reminder
                                                    @break
                                                @case('send_completion_notification')
                                                    Notify customer when collection is complete
                                                    @break
                                            @endswitch
                                        </p>
                                    </div>
                                    <div>
                                        @if($setting->type === 'boolean')
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                                                <input type="checkbox" name="settings[{{ $setting->key }}]" value="1"
                                                       {{ $setting->value == '1' ? 'checked' : '' }}
                                                       class="sr-only peer">
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-eco-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-eco-600"></div>
                                            </label>
                                        @else
                                            <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                                   class="w-20 px-3 py-2 rounded-lg border border-gray-200 focus:border-eco-500 focus:ring-eco-200 text-center">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">No notification settings configured.</p>
                        @endif
                    </div>
                </x-card>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <x-button type="submit" size="lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Settings
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>