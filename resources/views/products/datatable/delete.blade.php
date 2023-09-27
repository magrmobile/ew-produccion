<form action="{{ route('products.destroy', $id) }}" method="post">
    @csrf
    @method('DELETE')
    <input type="submit" name="submit" value="Eliminar" class="btn btn-danger btn-sm">
</form>