<?php

namespace App\Providers;

use App\Fulclum\Usecase\BulkDeleteFile;
use App\Fulclum\Usecase\BulkUploadFile;
use App\Fulclum\Usecase\CopyFile;
use App\Fulclum\Usecase\DeleteFile;
use App\Fulclum\Usecase\GetOne;
use App\Fulclum\Usecase\GetOneAvtiveRow;
use App\Fulclum\Usecase\Search;
use App\Fulclum\Usecase\UploadFile;
use App\Repositories\AdminRepository;
use App\Repositories\BannermenuRepository;
use App\Repositories\BannerRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CourseAttributeCategoryRepository;
use App\Repositories\CourseCategoryRepository;
use App\Repositories\CourseSubCategoryRepository;
use App\Repositories\CourseRepository;
use App\Repositories\EventAttributeCategoryRepository;
use App\Repositories\EventCategoryRepository;
use App\Repositories\EventRepository;
use App\Repositories\FeatureAttributeCategoryRepository;
use App\Repositories\FeatureCategoryRepository;
use App\Repositories\FeatureRepository;
use App\Repositories\FestivalRepository;
use App\Repositories\InformationRepository;
use App\Repositories\MainviewRepository;
use App\Repositories\PersonRepository;
use App\Repositories\PhotoGalleryRepository;
use App\Repositories\SearchSupportRepository;
use App\Repositories\SpotRepository;
use App\Repositories\StylesheetRepository;
use App\Repositories\SubCategoryRepository;
use App\Resolvers\DownloadResolver;
use App\Resolvers\GetOneResolver;
use App\Usecases\AcquireGpsFromAddress;
use App\Usecases\Downloads\EventContactToExcel;
use App\Usecases\Downloads\EventJoinToExcel;
use App\Usecases\Downloads\EventToExcel;
use App\Usecases\Downloads\SpotToExcel;
use App\Usecases\IsNewmark;
use Illuminate\Support\ServiceProvider;

class UsecaseProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {


        $this->app->bind('information.get_one', function () {
            $repository = $this->app->make(InformationRepository::class);
            return new GetOne($repository);
        });

        $this->app->bind('information.get_one_active', function () {
            $repository = $this->app->make(InformationRepository::class);
            return new GetOneAvtiveRow($repository);
        });

        $this->app->bind('information.search', function () {
            $repository = $this->app->make(InformationRepository::class);
            return new Search($repository);
        });




    }
}
