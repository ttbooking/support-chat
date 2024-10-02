<?php

use Illuminate\Support\Facades\Blade;

test('echos are compiled', function () {
    expect(Blade::compileString('@supportChat'))
        ->toBe('<?php echo TTBooking\SupportChat\SupportChat::register()->toHtml(); ?>');
});
