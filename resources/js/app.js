import './bootstrap';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard';

import './../../vendor/power-components/livewire-powergrid/dist/powergrid';
import './../../vendor/power-components/livewire-powergrid/dist/tailwind.css';

Alpine.plugin(Clipboard)

Livewire.start()


Livewire.on('success', function(){
    window.dispatchEvent(new CustomEvent("title='Success Notification'; type='success'; popToast()"));
});
