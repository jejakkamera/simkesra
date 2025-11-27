<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Registration;
use App\Models\UserTest;
use App\Moodle\MoodleUser;
use App\Models\MoodleApi;

class SyncMoodleTestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $registrations = \App\Models\Registration::whereDoesntHave('userTests')
            ->with('user:id,email') // Eager loading pada relasi user dan hanya mengambil id dan email
            ->select('user_id') // Hanya memilih user_id dari tabel registrations
            ->get();

        $site = MoodleApi::find(1);

        foreach ($registrations as $registration) {
            $moodleUser = new MoodleUser();
            $result = $moodleUser->getUserIdByEmail($registration->user->email);

            if ($result == null) {
                continue; // Jika user tidak ditemukan di Moodle, lanjutkan ke iterasi berikutnya
            } else {
                $quizResult = $moodleUser->getQuizGrades($result['id'], $site->quizid);

                if ($quizResult == null || empty($quizResult['attempts'])) {
                    continue; // Jika tidak ada hasil quiz, lanjutkan ke iterasi berikutnya
                } else {
                    // Cek apakah data userTest sudah ada
                    // dd($quizResult['attempts'][0]['sumgrades']);

                        $userTest = new UserTest([
                            'user_id' => $registration->user_id,
                            'id_elearning' => $result['id'],
                            'test_score' => $quizResult['attempts'][0]['sumgrades'],
                            'is_passed_prodi' => 'pending',
                            'is_published' => 'pending',
                        ]);

                        // Simpan ke database
                        $userTest->save();
                }
            }
        }
    }
}
