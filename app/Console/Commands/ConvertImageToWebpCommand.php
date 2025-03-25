<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Empresa;
use App\Models\Image;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Slider;
use Intervention\Image\Facades\Image as ImageIntervention;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ConvertImageToWebpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convertimage:webp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para convertir imágenes guardadas a formato webp';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->confirm('ASEGÚRATE PRIMERO DE TENER DESACTIVADO LA MARCA DE AGUA EN LAS IMÁGENES DE PRODUCTOS QUE YA ESTÁN REGISTRADOS, CONTINUAR ')) {
            $this->info('CONVERSIÓN CANCELADA POR EL USUARIO.');
            return;
        }

        $this->info("PROCESANDO IMÁGENES... (ESTO PUEDE TOMAR UN TIEMPO)");

        $empresa = view()->shared('empresa');
        $images = Image::orderBy('id')->get();
        $sliders = Slider::orderBy('id')->get();
        $urlMark = public_path('storage/images/company/' . $empresa->markagua);
        $dir = 'app/public/';

        $bar = $this->output->createProgressBar(count($images) + count($sliders));
        $bar->start();

        foreach ($images as $image) {
            try {
                $config = $this->getImageConfig($image->imageable_type);
                $originalPath = $config['pathdir'] . $image->url;
                $filePath = storage_path($dir . $originalPath);

                if (file_exists($filePath)) {
                    if ($image->imageable_type == Producto::class) {
                        $prodPathMobile = storage_path($dir . $config['pathdir'] . $image->urlmobile);
                        if (empty($image->urlmobile) || !file_exists($prodPathMobile)) {
                            $compressImageMobile = ImageIntervention::make($filePath)
                                ->resize(320, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                });

                            if ($empresa->usarMarkagua()) {
                                $margin = $empresa->alignmark !== 'center' ? 7 : 0;
                                $mark = ImageIntervention::make($urlMark)->resize(60, 60, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                })->orientate();
                                $compressImageMobile->insert($mark, $empresa->alignmark, $margin, $margin);
                            }

                            $filenameImageProd = uniqid($config['prefix'] . 'mobile_') . '.webp';
                            $compressImageMobile->save(public_path('storage/' . $config['pathdir'] . $filenameImageProd));
                            $image->urlmobile = $filenameImageProd;
                            if (str_ends_with($image->url, '.webp')) {
                                $image->save();
                            }
                        }
                    }

                    if (!str_ends_with($image->url, '.webp')) {
                        $compressedImage = ImageIntervention::make($filePath)
                            ->resize($config['width'], $config['height'], function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });

                        if ($config['allow_watermark'] && $empresa->usarMarkagua()) {
                            $w = $empresa->widthmark  ?? 100;
                            $h = $empresa->heightmark  ?? 100;
                            $urlMark = public_path('storage/images/company/' . $empresa->markagua);
                            $margin = $empresa->alignmark !== 'center' ? 20 : 0;
                            // create a new Image instance for inserting
                            $mark = ImageIntervention::make($urlMark)->resize($w, $h, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })->orientate();
                            $compressedImage->insert($mark, $empresa->alignmark, $margin, $margin);
                        }

                        $filename = uniqid($config['prefix']) . '.webp';
                        $compressedImage->save(public_path('storage/' . $config['pathdir'] . $filename));
                        $image->url = $filename;
                        $image->save();
                        Storage::delete($originalPath);
                    }
                } else {
                    $image->delete();
                }

                $bar->advance();
            } catch (\Exception $e) {
                $this->error("ERROR PROCESANDO IMÁGEN ID {$image->id}: " . $e->getMessage());
                Log::error("Error en procesamiento de imágen", [
                    'id' => $image->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

       $this->info("PROCESANDO IMÁGENES DE SLIDERS...");
        foreach ($sliders as $slider) {
            try {
                $pathdir = 'images/slider/';
                $filePathDesk = storage_path($dir . $pathdir . $slider->url);
                $filePathMobile = storage_path($dir . $pathdir . $slider->urlmobile);

                $originalPathDesk = $pathdir . $slider->url;
                $originalPathMobile = $pathdir . $slider->urlmobile;

                if (file_exists($filePathDesk)) {
                    if (!str_ends_with($slider->url, '.webp')) {
                        $filenameDesk = uniqid('slider_desk_') . '.webp';
                        $compressedDesk = ImageIntervention::make($filePathDesk)
                            ->resize(1920, 560, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                        $compressedDesk->save(public_path('storage/' . $pathdir . $filenameDesk));
                        $slider->url = $filenameDesk;
                        Storage::delete($originalPathDesk);
                    }
                }
                if (file_exists($filePathMobile)) {
                    if (!str_ends_with($slider->urlmobile, '.webp')) {
                        $filenameMobile = uniqid('slider_mobile_') . '.webp';
                        $compressedMobile = ImageIntervention::make($filePathMobile)
                            ->resize(720, 833, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                        $compressedMobile->save(public_path('storage/' . $pathdir . $filenameMobile));
                        $slider->urlmobile = $filenameMobile;
                        Storage::delete($originalPathMobile);
                    }
                }
                $slider->save();
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("ERROR AL PROCESAR SLIDER CON ID {$slider->id}: $filePathDesk " . $e->getMessage());
                Log::error("Error en procesamiento de slider", [
                    'id' => $slider->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        $bar->finish();
        // return Command::SUCCESS;
        return $this->info("IMÁGENES CONVERTIDAS A FORMATO WEBP CORRECTAMENTE.");
    }

    protected function getImageConfig($type): array
    {
        return match ($type) {
            Producto::class => [
                'pathdir' => 'images/productos/',
                'prefix' => 'producto_',
                'width' => 1200,
                'height' => null,
                'allow_watermark' => true
            ],
            Empresa::class => [
                'pathdir' => 'images/company/',
                'prefix' => '',
                'width' => 400,
                'height' => null,
                'allow_watermark' => false
            ],
            Category::class => [
                'pathdir' => 'images/categories/',
                'prefix' => 'category_',
                'width' => 100,
                'height' => null,
                'allow_watermark' => false
            ],
            Marca::class => [
                'pathdir' => 'images/marcas/',
                'prefix' => 'marca_',
                'width' => 150,
                'height' => null,
                'allow_watermark' => false
            ],
            default => [
                'pathdir' => 'images/productos/',
                'prefix' => 'producto_',
                'width' => 1200,
                'height' => null,
                'allow_watermark' => true
            ]
        };
    }
}
