<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'LandingController@landing');
Route::get('/classes', 'LandingController@classes');
Route::get('/blogs', 'LandingController@blogs');
Route::get('/home', 'HomeController@index');

Route::view('forgotpass', 'auth.forgotpass');

Route::get('/profile', 'ProfileController@index')->middleware('auth');
Route::post('/profile/update', 'ProfileController@update')->name('profile.update')->middleware('auth');
Route::post('/profile/update/socmed', 'ProfileController@updateSocMed')->name('profile.updateSocMed')->middleware('auth');

// Route::post('/class/class-list/check/{lessonId}', 'DetailClassController@viewStudents')->name('view_students.viewStudents')->middleware('auth');
// Route::post('/class/class-list/students/{lessonId}')->name('view_students.viewStudents')->middleware('auth');

 Route::get('/course/{lesson}/section/{section}', 'CourseSectionController@see_section')->name('course.see_section');
Route::any('/your-api-endpoint', 'ExamTakerController@submitQuiz');


Route::get('/datatable', function () {
    return view('blog.datatable');
});

Route::redirect('/course', '/classes');


Route::get('/template', function () {
    return view('template.atlantis');
});

Route::get('/loginz', function () {
    return view('neo_login');
});


// ROUTING SETELAH LOGIN
Route::group(['middleware’' => ['auth']], function () {

    Route::post('/dfef', 'ProfileController@updatePasswordz');
    Route::get('/class/class-list/view-class/{id}', 'DetailClassController@viewClass');
    Route::get('/class/class-list/students/{lessonId}', 'DetailClassController@viewStudents');
    Route::get('/class/class-list', 'ClassListController@classList');
    Route::get('/class/my-class', 'MyClassController@myClass');
    Route::get('/my-class/open/{lessonId}/section/{sectionId}', 'OpenClassController@openClass')->name('course.openClass');

    // Route::get('/class/class-list/', 'CountingController@countStudents');
    Route::post('/input-pin', 'ClassListController@validatePIN');
    Route::post('/class/class-list/students/{lessonId}', 'DetailClassController@viewStudents');

    // ROUTING KHUSUS MENTOR
    Route::group(['middleware' => ['mentor']], function () {


        Route::prefix("lesson")->group(function (){
            Route::get('category', ['uses' => 'LessonCategoryController@manage']);
            Route::get('category/create', ['uses' => 'LessonCategoryController@create']);
            Route::post('category/store', 'LessonCategoryController@store')->name('lesson_category.store');
            Route::any('category/{id}/delete', 'LessonCategoryController@destroy')->name('lesson_category.destroy');
            Route::get('category/{id}/update', 'LessonCategoryController@update')->name('lesson_category.update');
            Route::post('category/{id}/edit', 'LessonCategoryController@edit')->name('lesson_category.edit');
        });

        Route::get('/lesson/manage', ['uses' => 'LessonController@manage']);
        Route::get('/lesson/store', 'LessonController@add');
        Route::get('/lesson/{lesson}/students/', 'LessonController@seeStudent');
        Route::get('/lesson/correct', 'FinalProjectController@correction');
        Route::resource('lesson', LessonController::class);
        Route::resource('section', CourseSectionController::class);
        Route::get('/lesson/{lesson}/section/', 'CourseSectionController@manage_section');
        Route::get('/lesson/{lessonId}/section/{sectionId}/input-score', 'CourseSectionController@viewInputScore');
        // Route::post('/lesson/{lesson}/store/','CourseSectionController@store')->name('section.store');
        // Route::delete('/lesson/{lesson}/section/s','CourseSectionController@destroy')->name('section.delete');
        Route::get('/lesson/{lesson}/section/create', 'CourseSectionController@create_section');
        Route::get('/student/{id}/all-scores', 'ScoreController@seeByStudent');
        Route::post('/update-scores', 'CourseSectionController@updateScores');
        Route::post('/course/submission/scoring', 'FinalProjectController@score')->name('course.submission.scoring');

        Route::prefix("exam")->group(function (){
            Route::get('new', 'MentorExamController@viewCreateNew');
            Route::post('store', 'MentorExamController@storeNewExam');
            Route::post('store/quiz', 'MentorExamController@storeNewExam')->name('store.quiz');
            Route::post('update', 'MentorExamController@updateExam');
            Route::post('update-question', 'MentorExamController@updateQuestion');
            Route::get('manage', 'MentorExamController@viewManageExam');
            Route::get('manage-exam-v2', 'MentorExamController@viewManageExam_v2');
            Route::get('manage-exam-v2/create-exam', 'MentorExamController@viewCreateExam_v2');
            Route::get('manage-exam-v2/{examId}/load-exam', 'MentorExamController@viewLoadExam_v2');


            Route::delete('{id}/delete', 'MentorExamController@deleteExam')->name("exam.delete");
            Route::get('{id}/edit', 'MentorExamController@viewEditExam')->name("exam.edit");

            Route::get('session', 'MentorExamSessionController@viewManageSession');


            Route::prefix("session")->group(function (){
                Route::get('{id}/view', 'MentorExamSessionController@viewDetailSession');
                Route::any('{id}/mquestions', 'MentorExamSessionController@fetchQuestions');
                Route::any('{id}/edit', 'MentorExamSessionController@editSession');
                Route::any('{id}/data', 'MentorExamSessionController@getSessionData');
                Route::any('update', 'MentorExamSessionController@updateSessionData');
            });

            Route::get('{id}/question', 'MentorExamController@viewManageQuestion');
            Route::get('{id}/session', 'MentorExamSessionController@viewManageSession');

            Route::post('store-exam-session', 'MentorExamSessionController@storeSession');
            Route::get('refresh-session-table', 'MentorExamSessionController@getExamSession');
            Route::post('delete-exam-session', 'MentorExamSessionController@destroyExamSession');

            Route::get('question/{id}/edit', 'MentorExamController@viewEditQuestion');
            Route::post('question/{id}/delete', 'MentorExamController@deleteQuestion');

            Route::get('{id}/question-order', 'MentorExamController@viewManageQuestionOrder');
            Route::post('save-question', 'MentorExamController@storeQuestion');
            Route::post('save-question-to-db', 'MentorExamController@storeQuestion_v2')->name('store.question');

            Route::any('mquestions', 'MentorExamController@fetchQuestions');
            Route::any('question/edit-questions', 'MentorExamController@fetchQuestions')->name("edit_question");
            Route::any('update-question-order', 'MentorExamController@updateQuestionOrder')->name("update_question_order");
        });
    });


    Route::get('/progress', 'StudentProgressController@startSection');

    Route::prefix("quiz")->group(function (){

        Route::prefix("session")->group(function (){
            Route::get('{id}/initial', 'ExamTakerController@viewInitialTakeSession');
            Route::get('{id}/take-play', 'ExamTakerController@viewInitialTakeSession');
            Route::get('{id}/play', 'ExamTakerController@viewInitialTakeSession');
            Route::get('{id}/view', 'MentorExamSessionController@viewDetailSession');
            Route::any('{id}/mquestions', 'ExamTakerController@fetchQuestions');
            Route::any('save-answers', 'ExamTakerController@saveAnswers');
        });


        Route::any('{id}/mquestions', 'MentorExamSessionController@fetchQuestions');
    });

    // ROUTING KHUSUS STUDENTS
    Route::group(['middleware' => ['student']], function () {

        Route::get('/portfolio/see/{portfolio}/', 'PortfolioController@show')->name('portfolio.show');
        Route::get('/portfolio/create', 'PortfolioController@create')->name('portfolio.create');
        Route::get('/portfolio/manage', 'PortfolioController@manage')->name('portfolio.manage');
        Route::get('/portfolio/{portfolio}/edit', 'PortfolioController@edit')->name('portfolio.edit');
        Route::delete('/portfolio/{portfolio}/destroy', 'PortfolioController@destroy')->name('portfolio.destroy');
        Route::post('/portfolio/store', 'PortfolioController@store')->name('portfolio.store');
        Route::post('/portfolio/{portfolio}/update', 'PortfolioController@update')->name('portfolio.update');




        Route::post('/course/register', 'LessonController@studentRegister')->name('course.register');
        Route::post('/course/drop', 'LessonController@drop')->name('course.drop');
        Route::post('/submission', 'FinalProjectController@see_correction')->name('submission');
        Route::get('/course/{lesson}/submission', 'FinalProjectController@submit_page')->name('course.submission');
        Route::get('/course/{lesson}/certificate', 'FinalProjectController@certificate')->name('course.certificate');
        Route::post('/course/submission/store', 'FinalProjectController@store')->name('course.submission.store');
    });
});

Route::get('/lesson/{lesson}', 'LessonController@show')->name('lesson.show');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/blog/create', ['uses' => 'BlogController@create'])->middleware('auth');
    Route::get('/blog/manage', ['uses' => 'BlogController@index'])->middleware('auth');
    Route::get('/blog/store', 'BlogController@add')->middleware('auth');
});





Route::resource('blog', BlogController::class);
Route::get('/blog/{blog}', 'BlogController@show')->name('blog.read');


Route::group(['prefix' => 'filemanager'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});




Auth::routes();
