<?php

namespace Uteq\LaravelVoltWebPush;

use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Blade;

class AutoInjectWebpushAssets
{
    static function provide()
    {
        app('events')->listen(RequestHandled::class, function ($handled) {
            if (!str($handled->response->headers->get('content-type'))->contains('text/html')) return;
            if (!method_exists($handled->response, 'status') || $handled->response->status() !== 200) return;

            $html = $handled->response->getContent();

            if (str($html)->contains('</html>')) {
                $handled->response->setContent(static::injectAssets($html));
            }
        });
    }

    static function injectAssets($html)
    {
        $styles = '';
        $scripts = Blade::render(<<<'html'
        <script>
            window.vapidPublicKey = '{{ $vapidPublicKey }}';

            document.addEventListener("DOMContentLoaded", () => {
                if ('serviceWorker' in navigator && 'PushManager' in window) {
                    navigator.serviceWorker.register('/service-worker.js')
                        .then(function(registration) {
                            console.log('Service Worker registered with scope:', registration.scope, registration);
                        }).catch(function(error) {
                            console.error('Service Worker registration failed:', error);
                        });
                }
            });
        </script>
        html, [
            'vapidPublicKey' => config('webpush.vapid.public_key'),
        ]);


        $html = str($html);

        if ($html->test('/<\s*\/\s*head\s*>/i') && $html->test('/<\s*\/\s*body\s*>/i')) {
            return $html
                ->replaceMatches('/(<\s*\/\s*head\s*>)/i', $styles . '$1')
                ->replaceMatches('/(<\s*\/\s*body\s*>)/i', $scripts . '$1')
                ->toString();
        }

        return $html
            ->replaceMatches('/(<\s*html(?:\s[^>])*>)/i', '$1' . $styles)
            ->replaceMatches('/(<\s*\/\s*html\s*>)/i', $scripts . '$1')
            ->toString();
    }
}
