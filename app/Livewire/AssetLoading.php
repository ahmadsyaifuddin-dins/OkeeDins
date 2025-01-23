<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asset;

class AssetLoading extends Component
{
    public $assets = [];
    public $isLoading = true;

    public function mount()
    {
        $this->loadAssets();
    }

    public function loadAssets()
    {
        // Simulasi load data (ganti dengan query sesuai kebutuhan)
        $this->assets = Asset::all();
        $this->isLoading = false;
    }
    public function render()
    {
        return view('livewire.asset-loading');
    }
}
