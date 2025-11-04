<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\MahaMukabla;
use App\Models\Candidate;
use App\Models\ElectionResult;
use App\Models\Party;
use App\Models\HomeSection;

class ElectionMahaSection extends Component
{
    public $voteCounts;
    public $topParties;
    public $mahamukablas;
    public $candidates;
    public $parties;
    public $status;

    public function __construct()
    {
        // 🗳 Fetch data
        $this->voteCounts = ElectionResult::all();
        $this->topParties = ElectionResult::orderBy('seats_won', 'desc')->take(4)->get();
        $this->mahamukablas = MahaMukabla::all();
        $this->candidates = Candidate::all();
        $this->parties = Party::all();

        // 🔍 Check if this section is enabled in home_sections
        $this->status = HomeSection::where('title', 'ElectionMahaSection')->value('status') ?? 0;
    }

    public function render()
    {
        // If status = 1 → show component, else hide
        if ($this->status == 1) {
            return view('components.election-maha-section');
        }
        return ''; // Return nothing if hidden
    }
}
