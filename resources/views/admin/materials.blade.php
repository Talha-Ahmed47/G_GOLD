@php
    $materials = $materials ?? \App\Models\AdminMaterial::all();
@endphp


<div class="container mx-auto p-4">

    <h1 class="text-2xl font-bold mb-4">Materials Management</h1>

    <!-- Materials Table -->
    <table class="min-w-full bg-white shadow-md rounded mb-6">
        <thead>
            <tr>
                <th class="py-2 px-4">ID</th>
                <th class="py-2 px-4">Metal</th>
                <th class="py-2 px-4">Amount (g)</th>
                <th class="py-2 px-4">Buy Price</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($materials as $material)
                <tr class="border-t">
                    <td class="py-2 px-4">{{ $material->id }}</td>
                    <td class="py-2 px-4">{{ ucfirst($material->metal) }}</td>
                    <td class="py-2 px-4">{{ $material->amount }}</td>
                    <td class="py-2 px-4">{{ $material->buy_price }}</td>

                    <td class="py-2 px-4">

                        <!-- Update Form -->
                        <form action="{{ route('admin.materials.update', $material->id) }}"
                              method="POST"
                              class="inline-block">
                            @csrf
                            @method('PUT')

                            <input type="number"
                                   name="amount"
                                   value="{{ $material->amount }}"
                                   class="w-20 border rounded p-1"
                                   required>

                            <button type="submit"
                                    class="bg-blue-500 text-white px-2 py-1 rounded">
                                Update
                            </button>
                        </form>

                        <!-- Delete Form -->
                        <form action="{{ route('admin.materials.destroy', $material->id) }}"
                              method="POST"
                              class="inline-block ml-2">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="bg-red-500 text-white px-2 py-1 rounded"
                                    onclick="return confirm('Delete this material?')">
                                Delete
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add Material -->
    <h2 class="text-xl font-semibold mb-2">Add New Material</h2>

    <form action="{{ route('admin.materials.store') }}"
          method="POST"
          class="grid grid-cols-2 gap-4 max-w-md">

        @csrf

        <div>
            <label class="block mb-1" for="metal">Metal</label>

            <select name="metal"
                    id="metal"
                    class="w-full border rounded p-1"
                    required>

                <option value="gold">Gold</option>
                <option value="silver">Silver</option>
                <option value="platinum">Platinum</option>
                <option value="palladium">Palladium</option>
            </select>
        </div>

        <div>
            <label class="block mb-1" for="amount">Amount (g)</label>

            <input type="number"
                   name="amount"
                   id="amount"
                   class="w-full border rounded p-1"
                   required>
        </div>

        <div class="col-span-2">
            <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded">
                Add Material
            </button>
        </div>

    </form>

</div>

