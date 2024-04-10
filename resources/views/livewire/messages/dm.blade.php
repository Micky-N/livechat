<?php

use function Livewire\Volt\{state};

//

?>

<div class="h-full overflow-hidden bg-black/40">
    <livewire:dm.layout :rooms="$this->rooms" />
    @include('livewire.messages.container')
</div>
