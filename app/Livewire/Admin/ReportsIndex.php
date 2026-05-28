<?php

namespace App\Livewire\Admin;

use App\Models\Report;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ReportsIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = '';

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

    public function render()
    {
        $reports = Report::query()
            ->with('user')
            ->visibleToAdmin();

        if ($this->statusFilter) {
            $reports->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $reports->where(function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%')
                    ->orWhere('location', 'like', '%'.$this->search.'%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%'.$this->search.'%');
                    });
            });
        }

        $reports = $reports->latest()->paginate(10);

        return view('livewire.admin.reports-index', [
            'reports' => $reports,
            'stats' => $this->getStats(),
        ]);
    }

    // Method getStats HARUS ADA
    public function getStats()
    {
        $globalQuery = Report::query()->visibleToAdmin();

        return [
            'total' => $globalQuery->count(),
            'pending' => (clone $globalQuery)->where('status', 'pending')->count(),
            'diproses' => (clone $globalQuery)->where('status', 'diproses')->count(),
            'selesai' => (clone $globalQuery)->where('status', 'selesai')->count(),
            'ditolak' => (clone $globalQuery)->where('status', 'ditolak')->count(),
        ];
        // $filteredQuery = clone $baseQuery;

        // if ($this->statusFilter) {
        //     $filteredQuery->where('status', $this->statusFilter);
        // }
        // if ($this->search) {
        //     $filteredQuery->where(function ($query) {
        //         $query->where('title', 'like', '%' . $this->search . '%')
        //               ->orWhere('description', 'like', '%' . $this->search . '%')
        //               ->orWhere('location', 'like', '%' . $this->search . '%')
        //               ->orWhereHas('user', function ($q) {
        //                   $q->where('name', 'like', '%' . $this->search . '%');
        //               });
        //     });
        // }

        // return [
        //     'total' => $filteredQuery->count(),
        //     'pending' => (clone $filteredQuery)->where('status', 'pending')->count(),
        //     'diproses' => (clone $filteredQuery)->where('status', 'diproses')->count(),
        //     'selesai' => (clone $filteredQuery)->where('status', 'selesai')->count(),
        //     'ditolak' => (clone $filteredQuery)->where('status', 'ditolak')->count(),
        // ];
    }
}
