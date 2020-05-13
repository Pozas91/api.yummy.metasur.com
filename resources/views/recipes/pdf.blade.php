<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <style>
            .page-break {
                page-break-after: always;
            }

            .description p, .ingredients pre, .steps pre {
                white-space: pre-wrap; /* css-3 */
                white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
                white-space: -pre-wrap; /* Opera 4-6 */
                white-space: -o-pre-wrap; /* Opera 7 */
                word-wrap: break-word; /* Internet Explorer 5.5+ */
            }
        </style>
    </head>
    <body>
        @foreach($recipes as $recipe)
            <h1>{{$recipe->name}}</h1>

            <p class="created_at">
                <b>Creado el:</b>
                {{\Jenssegers\Date\Date::parse($recipe->created_at)->format('j \\de F \\de Y')}}
            </p>

            <p class="rations">
                <b>Raciones: </b>
                {{trans_choice('{1} :rations ración|[2, *] :rations raciones', $recipe->rations, ['rations' => $recipe->rations])}}
            </p>

            <p class="duration">
                <b>Duración estimada: </b>
                {{$recipe->duration}} min.
            </p>

            <div class="description">
                <h3>Descripción</h3>
                <p>{{$recipe->description}}</p>
            </div>

            <div class="ingredients">
                <h3>Ingredientes</h3>
                <pre>{{$recipe->ingredients}}</pre>
            </div>

            <div class="steps">
                <h3>Pasos</h3>
                <pre>{{$recipe->steps}}</pre>
            </div>

            <div class="tags">
                <h3>Etiquetas</h3>
                <ul>
                    @foreach($recipe->tags as $tag)
                        <li>{{$tag->name}}</li>
                    @endforeach
                </ul>
            </div>

            <span class="page-break"></span>
        @endforeach
    </body>
</html>
