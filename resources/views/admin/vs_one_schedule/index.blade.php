@extends('layouts.app', ['title' => '1 VS 1 Schedules', 'link' => 'admin.vs_one_schedule.index'])

@section('content')

<div class="row" style="min-height: 75vh">
  <section class="col-12">
      <div class="section-body">

          <div class="card">
              <div class="card-header d-flex align-items-center justify-content-between">
                  <h4><i class="ni ni-controller"></i> Solo</h4>
              </div>

              <div class="card-body">
                  <form action="{{ route('admin.vs_one_schedule.store') }}" method="POST" class="table-responsive">
                    @csrf
                    <div>
                        <button type="submit" class="btn btn-success">Create Match</button>
                    </div>
                      <table class="table table-bordered table-flush" id="datatable">
                          <thead class="thead-light">
                          <tr style="text-align: center">
                              <th scope="col">Selected Team</th>
                              <th scope="col">Booking Time</th>
                              <th scope="col">Player Name</th>
                              <th scope="col">Player Email</th>
                              <th scope="col">Cancel</th>
                          </tr>
                          </thead>
                          <tbody>
                              @forelse ($solos as $solo)
                                  <tr style="text-align: center">
                                      <td>
                                        <input class="form-check-input" type="checkbox" value={{ $solo->id }} name="teams[]" id="check-{{ $solo->id }}">
                                      </td>
                                      <td>
                                        {{ date('h:i A', strtotime($solo->created_at)) }}
                                      </td>
                                      <td>
                                        {{ $solo->player->name }}
                                      </td>
                                      <td>
                                        {{ $solo->player->email }}
                                      </td>
                                      <td>
                                        <a href="{{ route('admin.vs_one_schedule.refund', [$team->id]) }}" class="btn btn-danger">Refund</a>
                                      </td>
                                  </tr>
                              @empty
                                  <tr>
                                      <td colspan="2">No Data Found!</td>
                                  </tr>
                              @endforelse
                          </tbody>
                      </table>
                    </form>
              </div>
          </div>

          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4><i class="ni ni-controller"></i> Team</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.vs_one_schedule.store') }}" method="POST" class="table-responsive">
                  @csrf
                  <div>
                      <button type="submit" class="btn btn-success">Create Match</button>
                  </div>
                    <table class="table table-bordered table-flush" id="datatable-basic">
                        <thead class="thead-light">
                        <tr style="text-align: center">
                            <th scope="col">Selected Team</th>
                            <th scope="col">Booking Time</th>
                            <th scope="col">Player Name</th>
                            <th scope="col">Player Email</th>
                            <th scope="col">Cancel</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($teams as $team)
                                <tr style="text-align: center">
                                    <td>
                                      <input class="form-check-input" type="checkbox" value={{ $team->id }} name="teams[]" id="check-{{ $team->id }}">
                                    </td>
                                    <td>
                                      {{ date('h:i A', strtotime($team->created_at)) }}
                                    </td>
                                    <td>
                                      {{ $team->player->name }}
                                    </td>
                                    <td>
                                      {{ $team->player->email }}
                                    </td>
                                    <td>
                                      <a href="{{ route('admin.vs_one_schedule.refund', [$team->id]) }}" class="btn btn-danger">Refund</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">No Data Found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                  </form>
            </div>
        </div>

    </div>
  </section>
</div>


@endsection