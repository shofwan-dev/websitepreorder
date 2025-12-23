<div>
    {{-- Sales Notification Popup --}}
    <div 
        id="salesNotificationPopup"
        class="sales-notification-popup {{ $isVisible ? 'show' : '' }}"
        wire:poll.8s="showNotification"
        x-data="{ show: @entangle('isVisible') }"
        x-show="show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="transform translate-x-[-150%]"
        x-transition:enter-end="transform translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="transform translate-x-0"
        x-transition:leave-end="transform translate-x-[-150%]"
    >
        @if($currentSale)
        <div class="sales-notification-content">
            {{-- Close Button --}}
            <button type="button" class="sales-notification-close" wire:click="hideNotification">
                &times;
            </button>
            
            {{-- Product Image --}}
            <div class="sales-notification-image">
                <img 
                    src="{{ $currentSale['product_image'] }}" 
                    alt="{{ $currentSale['product_name'] }}"
                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($currentSale['product_name']) }}&size=60&background=d4a017&color=fff'"
                />
            </div>
            
            {{-- Notification Text --}}
            <div class="sales-notification-text">
                <div class="sales-notification-customer">
                    <strong>{{ $currentSale['customer_name'] }}</strong> 
                    <span class="text-muted">dari {{ $currentSale['city'] }}</span>
                </div>
                <div class="sales-notification-product">
                    baru saja membeli <strong>{{ Str::limit($currentSale['product_name'], 30) }}</strong>
                </div>
                <div class="sales-notification-time">
                    <i class="fas fa-clock"></i> {{ $currentSale['time_ago'] }}
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Auto-hide after 5 seconds --}}
    @if($isVisible)
    <script>
        setTimeout(function() {
            @this.call('hideNotification');
        }, 5000);
    </script>
    @endif

    <style>
        .sales-notification-popup {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 1050;
            max-width: 380px;
            opacity: 0;
            transform: translateX(-150%);
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .sales-notification-popup.show {
            opacity: 1;
            transform: translateX(0);
        }

        .sales-notification-content {
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid #d4a017;
            position: relative;
            animation: popIn 0.5s ease;
        }

        @keyframes popIn {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .sales-notification-close {
            position: absolute;
            top: 5px;
            right: 8px;
            background: none;
            border: none;
            font-size: 18px;
            color: #999;
            cursor: pointer;
            transition: color 0.2s;
            padding: 0;
            line-height: 1;
        }

        .sales-notification-close:hover {
            color: #333;
        }

        .sales-notification-image {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border: 2px solid #f0f0f0;
        }

        .sales-notification-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sales-notification-text {
            flex: 1;
            min-width: 0;
        }

        .sales-notification-customer {
            font-size: 13px;
            color: #333;
            margin-bottom: 3px;
        }

        .sales-notification-customer strong {
            color: #d4a017;
        }

        .sales-notification-customer .text-muted {
            font-size: 11px;
            color: #888;
        }

        .sales-notification-product {
            font-size: 12px;
            color: #555;
            margin-bottom: 3px;
            line-height: 1.3;
        }

        .sales-notification-product strong {
            color: #333;
        }

        .sales-notification-time {
            font-size: 10px;
            color: #999;
        }

        .sales-notification-time i {
            margin-right: 3px;
        }

        /* Mobile responsive */
        @media (max-width: 576px) {
            .sales-notification-popup {
                left: 15px;
                right: 15px;
                bottom: 15px;
                max-width: calc(100% - 30px);
            }

            .sales-notification-content {
                padding: 12px;
            }

            .sales-notification-image {
                width: 45px;
                height: 45px;
            }

            .sales-notification-customer {
                font-size: 12px;
            }

            .sales-notification-product {
                font-size: 11px;
            }
        }
    </style>
</div>
