<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;

class Logs extends Component
{
    use WithPagination;

    public $allLogs = [];
    public $logFile = null;
    public $selectedLevel = 'all';
    public $searchQuery = '';
    public $logLevels = ['DEBUG', 'INFO', 'NOTICE', 'WARNING', 'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY'];
    public $storageLogPath;
    public $perPage = 10;

    public function mount()
    {
        $this->storageLogPath = storage_path('logs');
        $this->loadLogs();
    }

    public function loadLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            $this->allLogs = [];
            $this->resetPage();
            return;
        }

        $content = File::get($logPath);
        $lines = explode("\n", $content);
        $logs = [];

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;
            
            // Parse Laravel log format: [YYYY-MM-DD HH:MM:SS] environment.LEVEL: message
            if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+(\w+)\.(\w+):\s+(.*)/', $line, $matches)) {
                $log = [
                    'timestamp' => $matches[1],
                    'environment' => $matches[2],
                    'level' => $matches[3],
                    'message' => $matches[4],
                    'raw' => $line
                ];

                // Filter by level
                if ($this->selectedLevel !== 'all' && strtoupper($log['level']) !== strtoupper($this->selectedLevel)) {
                    continue;
                }

                // Filter by search query
                if (!empty($this->searchQuery) && stripos($log['message'], $this->searchQuery) === false) {
                    continue;
                }

                $logs[] = $log;
            }
        }

        // Reverse to show latest first
        $this->allLogs = array_reverse($logs);
        $this->resetPage();
    }

    public function getLogsProperty()
    {
        $page = $this->page ?? 1;
        $items = collect($this->allLogs);
        $perPage = $this->perPage;
        $offset = ($page - 1) * $perPage;
        
        $paginatedItems = $items->slice($offset, $perPage)->all();
        
        return new \Illuminate\Pagination\Paginator(
            $paginatedItems,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    public function getTotalLogsProperty()
    {
        return count($this->allLogs);
    }

    public function filterByLevel($level)
    {
        $this->selectedLevel = $level;
        $this->loadLogs();
    }

    public function search()
    {
        $this->loadLogs();
    }

    public function clearLogs()
    {
        if (File::exists(storage_path('logs/laravel.log'))) {
            File::put(storage_path('logs/laravel.log'), '');
            $this->allLogs = [];
            $this->resetPage();
            session()->flash('message', 'Log file cleared successfully!');
        }
    }

    public function downloadLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            return response()->download($logPath, 'laravel-' . now()->format('Y-m-d-His') . '.log');
        }
    }

    public function render()
    {
        return view('livewire.dashboard.logs', [
            'logs' => $this->logs ?? collect([])
        ]);
    }
}
