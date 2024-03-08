
<form action="{{ route('process.saml') }}" method="post">
    @csrf

    <button type="submit" class="btn btn-success">
        <i class="bi bi-arrow-right"></i> Process
    </button>

</form>
