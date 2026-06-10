<?php

namespace App\Livewire\Siswa;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ReportsIndex extends Component
{
    use WithPagination;

    public $statusFilter = '';

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function resetPolling() {

    }

    public function render()
    {
        $reports = Report::where('user_id', Auth::id())
            ->visibleToUser()
            ->with('images')
            ->latest();

        if ($this->statusFilter) {
            $reports->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $reports->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%')
                    ->orWhere('location', 'like', '%'.$this->search.'%');
            });
        }

        $reports = $reports->paginate(6);

        return view('livewire.siswa.reports-index', [
            'reports' => $reports,
            'stats' => $this->getStats(),
        ]);
    }

    public function getStats() {
        $globalQuery = Report::query()->visibleToUser();
        return [
            'total' => $globalQuery->count(),
            'pending' => (clone $globalQuery)->where('status', 'pending')->count(),
            'diproses' => (clone $globalQuery)->where('status', 'diproses')->count(),
            'selesai' => (clone $globalQuery)->where('status', 'selesai')->count(),
            'ditolak' => (clone $globalQuery)->where('status', 'ditolak')->count(),
        ];
    }
}
