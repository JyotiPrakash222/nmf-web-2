@if (!empty($data['voteOptions']))
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h6 class="panel-title">
                {{ $data['voteTitle'] ?? 'Poll Question' }}
            </h6>
        </div>
        <div class="panel-body">
            <form>
                @csrf
                <input type="hidden" name="vote_id" value="{{ $data['voteId'] }}">

                <ul class="list-group vote-options">
                    @php $totalVotes = $data['voteTotal'] ?: 1; @endphp

                    @foreach ($data['voteOptions'] as $option => $count)
                        @php
                            $percent = round(($count / $totalVotes) * 100);
                            $optionId = Str::slug($option, '_');
                            $voteOptionModel = App\Models\VoteOption::where('vote_id', $data['voteId'])
                                ->where('name', $option)
                                ->first();
                        @endphp
                        <li class="border-0 d-flex align-items-start gap-2">
                            <input type="radio" class="form-check-input custom-radio" name="option_id"
                                value="{{ $voteOptionModel->id ?? '' }}" data-count="{{ $count }}">
                            <label for="vote_{{ $optionId }}" class="w-100">
                                <span class="option-text">{{ $option }}</span>
                                <div class="progress-container">
                                    <div class="progress-bar" data-option="{{ $voteOptionModel->id }}"
                                        data-count="{{ $count }}" style="width: {{ $percent }}%;"></div>
                                    <span class="percentage-text clickable-percentage">{{ $percent }}%</span>
                                </div>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </form>
        </div>
        <div class="panel-footer mt-3 d-flex flex-row justify-content-between align-items-center">
            <div class="vote-feedback text-danger" style="display: none;"></div>
            <div class="total-votes text-danger"></div>
        </div>
    </div>
@endif

<script>
    $(document).ready(function() {
        // Check localStorage for existing vote
        const voteId = $('input[name="vote_id"]').val();
        const hasVoted = localStorage.getItem(`voted_${voteId}`);

        if (hasVoted) {
            disableVoting();
            // Load existing results immediately
            fetchCurrentResults(voteId);
        }

        $('input[name="option_id"]').on('change', function() {
            const selectedOptionId = $(this).val();
            const voteId = $('input[name="vote_id"]').val();

            // Construct the correct route manually
          //  const routeTemplate = "{{ route('vote.submit', ['id' => '__id__']) }}";
          //const submitUrl = routeTemplate.replace('__id__', voteId);
        const baseUrl = "{{ rtrim(config('global.base_url'), '/') }}/";
const routeTemplate = baseUrl + "submit-vote/__id__";
const submitUrl = routeTemplate.replace("__id__", voteId);

            

            $('input[name="option_id"]').prop('disabled', true);

            $.ajax({
                url: submitUrl,
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    vote_id: voteId,
                    option_id: selectedOptionId,
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Store in localStorage that user has voted
                        localStorage.setItem(`voted_${voteId}`, 'true');

                        updateResults(response.results, response.totalVotes);
                        disableVoting();

                        $('.vote-feedback').show().text('आप वोट कर चुके हैं');
                        $('.total-votes').text(response.totalVotes + ' Votes');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403 && xhr.responseJSON?.already_voted) {
                        // Store in localStorage that user has voted
                        localStorage.setItem(`voted_${voteId}`, 'true');
                        disableVoting();
                        $('.vote-feedback').show().text(xhr.responseJSON.message);
                    } else {
                        alert(xhr.responseJSON?.message || 'Error saving vote.');
                        $('input[name="option_id"]').prop('disabled', false);
                    }
                }
            });
        });

        function updateResults(results, totalVotes) {
            $('.progress-bar').each(function() {
                const optionId = $(this).data('option');
                const count = results[optionId] || 0;
                const percent = Math.round((count / totalVotes) * 100);
                $(this).css('width', percent + '%');
                $(this).siblings('.percentage-text').text(percent + '%');
                $(this).attr('data-count', count);
            });
            $('.progress-container').show();
        }

        function disableVoting() {
            $('input[name="option_id"]').prop('disabled', true);
            $('.vote-feedback').show().text('आप वोट कर चुके हैं');
        }

        // Add this new function
        function fetchCurrentResults(voteId) {
            $.ajax({
                url: `/api/vote/results/${voteId}`, // You'll need to create this endpoint
                method: "GET",
                success: function(response) {
                    updateResults(response.results, response.totalVotes);
                    $('.total-votes').text(response.totalVotes + ' Votes');
                },
                error: function(xhr) {
                    console.error("Error fetching results:", xhr.responseText);
                }
            });
        }
    });
</script>
