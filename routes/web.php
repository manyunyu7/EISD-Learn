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
    Route::get('/portfolios', 'LandingController@portfolios');

    Route::get('/profile', 'ProfileController@index')->middleware('auth');
    Route::post('/profile', 'ProfileController@update')->name('profile.update')->middleware('auth');

    Route::get('/course/{lesson}/section/{section}', 'CourseSectionController@see_section')->name('course.see_section');


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


    Route::group(['middleware’' => ['auth']], function () {

        Route::post('/dfef', 'ProfileController@updatePasswordz');

        Route::group(['middleware' => ['mentor']], function () {
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
                Route::get('manage', 'MentorExamController@viewManageExam');
                Route::get('{id}/question', 'MentorExamController@viewManageQuestion');
                Route::post('save-question', 'MentorExamController@storeQuestion');
                Route::any('mquestions', 'MentorExamController@fetchQuestions');
                Route::any('question/edit-questions', 'MentorExamController@fetchQuestions')->name("edit_question");
                Route::any('update-question-order', 'MentorExamController@updateQuestionOrder')->name("update_question_order");
            });
        });


        Route::get('/progress', 'StudentProgressController@startSection');


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
