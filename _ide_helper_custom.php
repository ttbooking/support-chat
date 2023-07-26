<?php

namespace Illuminate\Foundation
{
    class Vite
    {
        /**
         * @return $this
         */
        public function supportChatEntryPoint(): static
        {
            return $this;
        }

        public function supportChatImage(string $asset): string
        {
            return '';
        }
    }
}
