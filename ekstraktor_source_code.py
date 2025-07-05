import os
import re

# Frasa spesifik yang akan disisipkan setelah setiap blok kelas
#SPECIFIC_PHRASE = "saya akan memebrikan beberapa kode yang telah saya buat. jangan melakukan analisis atau memberikan kode yang sudah dimodifikasi untuk memenuhi alur diatas sebelum saya mengatakan kata \"selesai sudah\""
SPECIFIC_PHRASE = " "

def extract_classes_from_content(file_content):
    """
    Ekstrak semua definisi kelas dari konten file.
    Mengembalikan daftar string yang diformat: "nama_class{\n //kode\n}\nPHRASE"
    """
    extracted_classes = []
    # Regex untuk menemukan deklarasi class: `class ClassName {`
    # Ini mencari 'class', diikuti oleh spasi, lalu nama kelas (word char),
    # lalu mengabaikan karakter apa pun yang bukan '{' sampai menemukan '{'
    # Pattern ini dirancang untuk menangani deklarasi kelas PHP umum.
    class_pattern = re.compile(r"class\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*(?:[^{]*)\{")

    offset = 0
    while True:
        # Cari deklarasi kelas berikutnya dari posisi `offset`
        match = class_pattern.search(file_content, offset)
        if not match:
            break # Tidak ada deklarasi kelas lagi

        class_name = match.group(1) # Ambil nama kelas dari grup tangkapan regex
        start_of_brace_idx = match.end() - 1 # Posisi karakter '{' pembuka

        brace_level = 1 # Mulai dengan 1 untuk '{' pembuka kelas saat ini
        class_body_start_idx = start_of_brace_idx + 1
        class_body_end_idx = -1

        # Iterasi melalui konten setelah '{' pembuka untuk menemukan '{' penutup yang cocok
        # Ini menangani kurung kurawal bersarang ({...{...}...})
        for i in range(class_body_start_idx, len(file_content)):
            char = file_content[i]
            if char == '{':
                brace_level += 1
            elif char == '}':
                brace_level -= 1

            if brace_level == 0:
                class_body_end_idx = i # Ditemukan kurung kurawal penutup yang cocok
                break
        
        if class_body_end_idx != -1:
            # Ekstrak kode mentah di dalam kurung kurawal kelas
            class_code_raw = file_content[class_body_start_idx:class_body_end_idx].strip()
            
            # Indentasi setiap baris kode kelas dengan 4 spasi
            indented_lines = []
            for line in class_code_raw.splitlines():
                indented_lines.append("    " + line)
            
            indented_class_code = "\n".join(indented_lines)
            
            # Pastikan ada baris baru setelah blok kode yang diindentasi jika tidak kosong
            if indented_class_code:
                indented_class_code += "\n"

            # Format output untuk kelas ini, termasuk frasa spesifik
            formatted_class_block = (
                f"{class_name}{{\n" # Nama kelas dan '{' pembuka
                f"{indented_class_code}" # Kode kelas yang diindentasi
                f"}}\n" # '{' penutup
                f"{SPECIFIC_PHRASE}\n" # Frasa spesifik setelah blok kelas
            )
            extracted_classes.append(formatted_class_block)
            offset = class_body_end_idx + 1 # Lanjutkan pencarian dari setelah kelas ini
        else:
            # Tidak dapat menemukan kurung kurawal penutup yang cocok untuk kelas ini, lewati
            offset = match.end() # Pindahkan offset melewati kecocokan saat ini untuk menghindari loop tak terbatas

    return extracted_classes

def main():
    """
    Fungsi utama untuk menjalankan ekstraksi kelas dengan format output yang diminta.
    """
    # Ganti path ini dengan path absolut ke folder proyek Anda (misalnya, proyek Laravel Anda).
    # Jika skrip ini berada di root proyek Anda (di mana folder 'App', 'resources', dll. berada),
    # maka '.' sudah benar.
    project_root = "." 

    # Daftar direktori relatif yang akan dipindai untuk kelas
    directories_to_scan_relative = [
        os.path.join('app', 'Http', 'Controllers'),
        os.path.join('app', 'Models'),
        os.path.join('database', 'migrations'),
        os.path.join('resources', 'views'), # Meskipun tampilan biasanya berisi blok PHP, definisi kelas penuh lebih jarang.
        os.path.join('routes') # Biasanya file PHP, definisi kelas penuh juga lebih jarang.''
    ]

    all_extracted_content = []

    print("Memulai proses ekstraksi kelas dengan format yang lebih kompleks...\n")

    for rel_path_dir in directories_to_scan_relative:
        full_directory_path = os.path.join(project_root, rel_path_dir)
        # Format nama direktori untuk tampilan (misalnya, App/Http/Controllers)
        directory_display_name = os.path.normpath(rel_path_dir).replace(os.sep, '/')
        
        if not os.path.isdir(full_directory_path):
            all_extracted_content.append(f"// Direktori tidak ditemukan: {directory_display_name}\n\n")
            continue

        # Menambahkan header untuk direktori untuk memberikan konteks dalam file output
        all_extracted_content.append(f"// --- Konten dari Direktori: {directory_display_name} ---\n")

        # Melintasi semua file dalam direktori dan sub-direktorinya
        for root, _, files in os.walk(full_directory_path):
            files.sort() # Memastikan urutan file yang konsisten untuk output

            for file_name in files:
                file_path = os.path.join(root, file_name)
                # Dapatkan path relatif dari project_root untuk referensi dalam output
                relative_file_path_display = os.path.normpath(os.path.relpath(file_path, project_root)).replace(os.sep, '/')
                
                try:
                    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
                        file_content = f.read()
                        # Ekstrak kelas dari konten file saat ini
                        extracted_classes = extract_classes_from_content(file_content)
                        
                        if extracted_classes:
                            # Jika ada kelas yang ditemukan, tambahkan header file
                            all_extracted_content.append(f"// FILE: {relative_file_path_display}\n")
                            all_extracted_content.extend(extracted_classes)
                            all_extracted_content.append("\n") # Tambahkan baris kosong setelah semua kelas dari satu file
                        # Jika tidak ada kelas yang ditemukan dalam file ini, tidak ada output untuk file tersebut.
                except Exception as e:
                    # Tangani kesalahan pembacaan file
                    all_extracted_content.append(f"// Gagal membaca file {relative_file_path_display}: {e}\n\n")
        
        # Tambahkan pemisah setelah setiap direktori selesai diproses
        all_extracted_content.append(f"// --- Akhir Konten Direktori: {directory_display_name} ---\n\n")


    # Menulis semua kode kelas yang diekstrak ke dalam satu file output
    output_file_name = "extracted_classes_with_phrase.txt"
    try:
        with open(output_file_name, 'w', encoding='utf-8') as outfile:
            outfile.writelines(all_extracted_content)
        print(f"\nProses ekstraksi selesai!")
        print(f"Seluruh kelas telah disimpan ke dalam file: {output_file_name}")
    except Exception as e:
        print(f"\nTerjadi kesalahan saat menulis file output: {e}")

if __name__ == "__main__":
    main()
 