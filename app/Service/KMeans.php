<?php

namespace App\Services;

/**
 * Implementasi algoritma K-Means Clustering sederhana (murni PHP).
 *
 * Fitur yang dipakai (setelah standarisasi z-score):
 *  - partisipasi (%)
 *  - dpt_jml
 *  - disabilitas
 *
 * Cocok untuk dataset skala ratusan-ribuan baris seperti data TPS.
 */
class KMeans
{
    protected int $k;
    protected int $maxIterations;
    protected float $tolerance;

    public function __construct(int $k = 7, int $maxIterations = 200, float $tolerance = 1e-6)
    {
        $this->k = $k;
        $this->maxIterations = $maxIterations;
        $this->tolerance = $tolerance;
    }

    /**
     * @param array<int, array<int, float>> $points  Setiap elemen: array fitur numerik
     * @return array{labels: int[], centroids: array<int, array<int,float>>}
     */
    public function fit(array $points): array
    {
        $n = count($points);
        if ($n === 0) {
            return ['labels' => [], 'centroids' => []];
        }

        $normalized = $this->standardize($points);

        $centroids = $this->initCentroids($normalized, $this->k);
        $labels = array_fill(0, $n, 0);

        for ($iter = 0; $iter < $this->maxIterations; $iter++) {
            $changed = false;

            // Assignment step
            foreach ($normalized as $i => $p) {
                $best = 0;
                $bestDist = INF;
                foreach ($centroids as $c => $centroid) {
                    $d = $this->euclidean($p, $centroid);
                    if ($d < $bestDist) {
                        $bestDist = $d;
                        $best = $c;
                    }
                }
                if ($labels[$i] !== $best) {
                    $changed = true;
                }
                $labels[$i] = $best;
            }

            // Update step
            $newCentroids = $this->recomputeCentroids($normalized, $labels, $this->k, count($points[0]));

            $shift = 0.0;
            foreach ($newCentroids as $c => $nc) {
                $shift += $this->euclidean($nc, $centroids[$c]);
            }
            $centroids = $newCentroids;

            if (!$changed || $shift < $this->tolerance) {
                break;
            }
        }

        return ['labels' => $labels, 'centroids' => $centroids];
    }

    protected function standardize(array $points): array
    {
        $n = count($points);
        $dims = count($points[0]);
        $mean = array_fill(0, $dims, 0.0);
        $std = array_fill(0, $dims, 0.0);

        foreach ($points as $p) {
            foreach ($p as $d => $v) {
                $mean[$d] += $v;
            }
        }
        foreach ($mean as $d => $v) {
            $mean[$d] = $v / $n;
        }

        foreach ($points as $p) {
            foreach ($p as $d => $v) {
                $std[$d] += ($v - $mean[$d]) ** 2;
            }
        }
        foreach ($std as $d => $v) {
            $std[$d] = sqrt($v / $n) ?: 1.0;
        }

        $result = [];
        foreach ($points as $p) {
            $row = [];
            foreach ($p as $d => $v) {
                $row[$d] = ($v - $mean[$d]) / $std[$d];
            }
            $result[] = $row;
        }

        return $result;
    }

    protected function initCentroids(array $points, int $k): array
    {
        // K-Means++ sederhana untuk inisialisasi centroid yang lebih stabil
        $n = count($points);
        $centroids = [];
        $firstIdx = array_rand($points);
        $centroids[] = $points[$firstIdx];

        while (count($centroids) < $k) {
            $distances = [];
            foreach ($points as $p) {
                $minDist = INF;
                foreach ($centroids as $c) {
                    $minDist = min($minDist, $this->euclidean($p, $c));
                }
                $distances[] = $minDist ** 2;
            }
            $sum = array_sum($distances) ?: 1.0;
            $r = mt_rand() / mt_getrandmax() * $sum;
            $acc = 0.0;
            $chosen = 0;
            foreach ($distances as $i => $d) {
                $acc += $d;
                if ($acc >= $r) {
                    $chosen = $i;
                    break;
                }
            }
            $centroids[] = $points[$chosen];
        }

        return $centroids;
    }

    protected function recomputeCentroids(array $points, array $labels, int $k, int $dims): array
    {
        $sums = array_fill(0, $k, null);
        $counts = array_fill(0, $k, 0);

        foreach ($points as $i => $p) {
            $c = $labels[$i];
            if ($sums[$c] === null) {
                $sums[$c] = array_fill(0, $dims, 0.0);
            }
            foreach ($p as $d => $v) {
                $sums[$c][$d] += $v;
            }
            $counts[$c]++;
        }

        $centroids = [];
        foreach ($sums as $c => $sum) {
            if ($sum === null || $counts[$c] === 0) {
                // centroid kosong: pilih titik acak agar tidak hilang
                $centroids[$c] = $points[array_rand($points)];
                continue;
            }
            $centroids[$c] = array_map(fn ($v) => $v / $counts[$c], $sum);
        }

        return $centroids;
    }

    protected function euclidean(array $a, array $b): float
    {
        $sum = 0.0;
        foreach ($a as $i => $v) {
            $sum += ($v - $b[$i]) ** 2;
        }
        return sqrt($sum);
    }
}