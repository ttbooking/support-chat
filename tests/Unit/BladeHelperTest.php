<?php

use Illuminate\Support\Facades\Blade;

test('echos are compiled', function () {
    expect(Blade::compileString('@chat'))
        ->toBe('<?php echo TTBooking\\SupportChat\\SupportChat::standalone()->toHtml(); ?>')

        ->and(Blade::compileString('@chat()'))
        ->toBe('<?php echo TTBooking\\SupportChat\\SupportChat::standalone()->toHtml(); ?>')

        ->and(Blade::compileString("@chat('hello')"))
        ->toBe("<?php echo TTBooking\\SupportChat\\SupportChat::standalone('hello')->toHtml(); ?>")

        ->and(Blade::compileString('@winchat'))
        ->toBe('<?php echo TTBooking\\SupportChat\\SupportChat::windowed()->toHtml(); ?>')

        ->and(Blade::compileString('@winchat()'))
        ->toBe('<?php echo TTBooking\\SupportChat\\SupportChat::windowed()->toHtml(); ?>');
});
