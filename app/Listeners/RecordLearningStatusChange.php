<?php

namespace App\Listeners;

use App\Events\LearningStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecordLearningStatusChange
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\LearningStatusChanged  $event
     * @return void
     */
    public function handle(LearningStatusChanged $event)
    {
        // Periksa perubahan pada kolom learning_status
        if ($event->originalLearningStatus == 0 && $event->newLearningStatus == 1) {
            // Jika nilai sebelumnya adalah 0 dan nilai setelahnya adalah 1, maka catat waktu perubahan
            $learningStatusChangeTime = Carbon::now(); // Gunakan Carbon untuk mendapatkan waktu saat ini

            // Simpan waktu perubahan ke dalam tabel log (Anda bisa menggunakan model untuk ini)
            // Contoh: LearningStatusChangeLog::create(['learning_status_change_time' => $learningStatusChangeTime]);
        }
    }
}
