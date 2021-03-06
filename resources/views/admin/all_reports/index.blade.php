@extends('layouts.admin_master')

@section('run_custom_css_file')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            <div>
                <h2>All Reports</h2>
            </div>

            <div class="text-center">
                <h2 class="alert alert-danger">Last You have published a article {{$last_article}}. Publish new article as early as possible.</h2>
            </div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-6">
                    <h5>Active Writers</h5>
                    <div class="alert alert-success">
                        @foreach($active_writers as $active_writer )
                            <div class="bottom10">
                                <div><b>{{ $active_writer->name }}:</b></div>
                                <div>
                                    @if(count($active_writer->articles->where('status', false)) > 0)
                                        @foreach($active_writer->articles->where('status', false) as $key => $article_item)
                                            <div>*
                                                <span class="btn-xs btn-danger right5">
                                                    {{ empty($article_item->article_deadline) ? 'not set': Carbon\Carbon::parse($article_item->article_deadline)->format('m-d-Y') }}
                                                </span>
                                                <a href="{{ route('admin_articles.edit', $article_item->id) }}" target="_blank" class="{{ $article_item->waiting_for_approval? 'red' : '' }}">
                                                    {{ str_limit(strip_tags($article_item->title), $limit = 60, $end = '...') }}
                                                </a>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="red"><b>No article</b></span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($role_type_name == 'admin')
                        <h5>Sell:</h5>
                        <div class="alert alert-success">
                            <div class="bottom5">
                                Total whole sell: {{ $total_whole_sell * config('constants.dollar_rate') }} BDT
                            </div>
                            <span class="btn btn-success">
                                Total sell from article: {{ $total_sell_from_article * config('constants.dollar_rate') }} BDT
                            </span>
                            <span class="btn btn-success">
                                Total sell from non articles: {{ $total_sell_from_non_article * config('constants.dollar_rate') }} BDT
                            </span>
                        </div>


                        <h5>
                            Cost:
                        </h5>
                        <div class="alert alert-danger">
                            <div class="bottom5">Total Cost: {{ $total_costs }} BDT</div>
                            @foreach($individual_costs as $key => $value)
                                <span class="btn btn-danger">{{ $key }} cost: {{ $value }} BDT</span>
                            @endforeach
                        </div>

                        @foreach($all_costs as $key=> $value)
                            <div class="alert alert-danger">{{ $key }} cost: {{ $value }} BDT</div>
                        @endforeach
                    @endif
                </div>
                <div class="col-md-6">
                    <h5>Article publish ratio (Total {{ $articles->count() }}) (View: {{ number_format($article_view_count) }})</h5>
                    <div class="row">
                        @foreach($total_articles as $key => $value)
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <div>{{ $key }}</div>
                                    <div class="btn btn-warning top5">Articles: {{ $value }}</div>
                                    @if($role_type_name == 'admin')
                                        <div class="btn btn-success top5">
                                        <span>Total sell:</span>
                                        @if(isset($monthly_product_sell[$key]))
                                            <span>{{ $monthly_product_sell[$key] * config('constants.dollar_rate') }} BDT</span>
                                        @else
                                            <span>0 BDT</span>
                                        @endif
                                    </div>
                                        <div class="btn btn-info top5">
                                            <span>Article return:</span>
                                            @if(isset($article_investment_return[$key]))
                                                <span>{{ $article_investment_return[$key] * config('constants.dollar_rate') }} BDT</span>
                                            @else
                                                <span>0 BDT</span>
                                            @endif
                                        </div>
                                    @endif


                                    <div class="btn btn-default top5">
                                        <span>Article View Count:</span>
                                        @if(isset($monthly_article_view_count[$key]))
                                            <span>{{ $monthly_article_view_count[$key] }}</span>
                                        @else
                                            <span>0</span>
                                        @endif
                                    </div>

                                    @if($role_type_name == 'admin')
                                        <div class="btn btn-danger top5">
                                        <span>Total cost:</span>
                                        @if(isset($monthly_site_cost[$key]))
                                            <span>{{ $monthly_site_cost[$key] }} BDT</span>
                                        @else
                                            <span>0 BDT</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <h5>Who has written articles</h5>
                    <div class="row">
                        @foreach($individual_articles as $key => $value)
                            <div class="col-md-5 btn btn-warning top5 left5">
                                <div class="alert alert-info">
                                    {{ $key }}
                                    <div>
                                         Total Article: {{ $value }}
                                    </div>
                                    <div>
                                        No Selling Article: {{ $individual_no_sell[$key] }}
                                    </div>
                                    @if($role_type_name == 'admin')
                                        <div>
                                            Total Cost: {{ $individual_cost[$key] }}
                                        </div>
                                        <div>
                                            Total Revenue: {{ $individual_revenue[$key] * config('constants.dollar_rate') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('run_custom_js_file')
@endsection

@section('run_custom_jquery')

@endsection