<?php
/**
 * PHP solution to Closest Pair Problem
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

Class ClosestPair {
	
	/**
	 * Function findClosestPairUsingBruteForce
	 *
	 * Finds closest pair using brute force approach. It runs as Order of n square
	 *
	 * @param array $pts  // A multi dimensional array containg points to find closest pair
	 *
	 * @return array // Returns an array with distance at index 0 and first point at index 1 and second at index 2
	 */
	public function findClosestPairUsingBruteForce(array $pts ) {
	
		if (count($pts)<2) return array(INF); // If there is only one or no element then return Infinite biggest number
		// Calculate distance
		$c = $this->calcDistance($pts[0], $pts[1]);
		// Prepare array with distance and first two points
		$r = array($c,$pts[0],$pts[1]);
		// Loop through all points
		for($i=0; $i < count($pts)-1; $i++) {
			for($j=$i+1;$j<count($pts);$j++) {
				// Calculate distance
				$t = $this->calcDistance($pts[$i], $pts[$j]);
				// If distance is less then minimum distance
				if($t<$c) {
					// Mark current distance as minumum
					$c = $t;
					// Fill return array
					$r = array($t,$pts[$i], $pts[$j]);
				}
			}
		}
		//return array data
		return $r;
	}
	
	/**
	 * Function findClosestPairUsingDivideAndConquer
	 *
	 * Finds closest pair using divide and conquer approach. It runs as O(nlogn)
	 *
	 * @param array $pts  // A multi dimensional array containg points to find closest pair
	 *
	 * @return array // Returns an array with distance at index 0 and first point at index 1 and second at index 2
	 */
	function findClosestPairUsingDivideAndConquer(array $points) {
		
		$sortbyyaxis = $points;
		$sortbyxaxis = $points;
		// Sort points using x asix
		uasort($sortbyxaxis, array('ClosestPair','sortByXaxis'));
		// Sort points using y asix
		uasort($sortbyyaxis, array('ClosestPair','sortByYaxis'));
		// Call funcrion by passing it sorted array
		return $this->findClosestPair($sortbyxaxis, $sortbyyaxis);
		
	}
	
	/**
	 * Function findClosestPair
	 *
	 * Finds closest pair using divide and conquer approach. It runs as O(nlogn)
	 *
	 * @param array $xP  // Array sorted by x axis
	 * 
	 * @param array $yP  // Array sorted by y axis
	 *
	 * @return array // Returns an array with distance at index 0 and first point at index 1 and second at index 2
	 */
	 private function findClosestPair(array $xP, array $yP) {
	
		$pR = $pL = $minR = $minL = array();
		$yR = $yL = $joiningStrip = $tDist = $minDist = array();
	
		$middleVLine = array();
		$i = $nP = $k = 0;
		// If elements are less than or equal to 3 then find using brute force approach
		if ( count($xP) <= 3 ) {
			return $this->findClosestPairUsingBruteForce($xP);
		}
		else {
	
			// Get middle of the array
			$midx = (int)ceil(count($xP)/2.0);
			// Cut the first part to PL
			$pL = array_slice($xP, 0, $midx);
			// Cut the remaining part to pR
			$pR = array_slice($xP, $midx, count($xP) - $midx);
			// get x coordinate of middle point of the array
			$middleVLine =  $pL[$midx-1]['x'];
			// Divide using middle line
			for($i=0; $i < count($yP); $i++) {
	
				if($yP[$i]['x'] <= $middleVLine) {
					$yL[] = $yP[$i];
				}
				else {
					$yR[] = $yP[$i];
				}
			}
			// Call function recursively to narrow down points
			$minR = $this->findClosestPair($pR, $yR);
			// Call function recursively to narrow down points
			$minL = $this->findClosestPair($pL, $yL);
			// Find minimum point
			$minDist = $this->findMinBetween($minR, $minL);
			// Calculate joining strip
			for($i=0; $i < count($yP); $i++) {
					if(abs($yP[$i]['x']-$middleVLine) < $minDist[0]) {
					$joiningStrip[] = $yP[$i];
				}
			}
	
			$tDist = $minDist;
			$nP = count($joiningStrip);
			// Loop through points to calulate points having minimum distance
			for($i=0; $i < ($nP - 1); $i++) {
				$k = $i + 1;
				while(($k < $nP) && (($joiningStrip[$k]['y'] - $joiningStrip[$i]['y']) < $minDist[0])){
					// Calculate distance
					$distance =  $this->calcDistance($joiningStrip[$i], $joiningStrip[$k]);
					if($distance < $tDist[0]) {
						$tDist = array($distance,$joiningStrip[$i],$joiningStrip[$k]);
					}
					$k++;
				}
			}
			// return array
			return $tDist;
		}
	}
	
	/**
	 * Function calcDistance
	 *
	 * Calcuates distance between two points
	 *
	 * @param array $point1  // First point array
	 *
	 * @param array $point2  // Second point array
	 *
	 * @return Distance between two points
	 */
	 private function calcDistance(array $point1, array $point2) {
	
		$distance = sqrt(pow($point1['x']-$point2['x'],2) + pow($point1['y']-$point2['y'],2));
		return $distance;
	}
	
	/**
	 * Function findMinBetween
	 *
	 * Finds minimum point
	 *
	 * @param array $minA  // First point array
	 *
	 * @param array $minB  // Second point array
	 *
	 * @return array containing minimum point
	 */
	 private function findMinBetween(array $minA,array  $minB) {
		// If point 1 is less than point 2 then return point 1
		if($minA[0]<$minB[0])
			return $minA;
		else return $minB; // return point 2
	}
	
	/**
	 * Function sortByXaxis
	 *
	 * Sorts points using x axis
	 *
	 * @return either 0 or 1 or -1 depending on value
	 */
	private static function sortByXaxis($a,$b) {
	
		if ($a['x'] == $b['x']) {
			return 0;
		}
		return ($a['x'] < $b['x']) ? -1 : 1;
	}
	/**
	 * Function sortByYaxis
	 *
	 * Sorts points using y axis
	 *
	 * @return either 0 or 1 or -1 depending on value
	 */
	private static function sortByYaxis($a,$b) {
	
		if ($a['y'] == $b['y']) {
			return 0;
		}
		return ($a['y'] < $b['y']) ? -1 : 1;
	}

}